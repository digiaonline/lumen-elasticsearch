<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;

/**
 * Class WildcardQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\TermLevel
 *
 * @see     https://www.elastic.co/guide/en/elasticsearch/reference/2.4/query-dsl-wildcard-query.html
 */
class WildcardQuery extends AbstractQuery
{
    use HasBoost;

    /**
     * @var string
     */
    private $value;

    /**
     * @inheritdoc
     *
     * @throws InvalidArgument
     */
    public function toArray()
    {
        if (!isset($this->field) || !isset($this->value)) {
            throw new InvalidArgument('"field" and "value" must be set for this type of query');
        }

        $definition = [
            'value' => $this->getValue(),
        ];

        if ($this->hasBoost()) {
            $definition['boost'] = $this->getBoost();
        }

        return [
            'wildcard' => [
                $this->getField() => $definition,
            ],
        ];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
