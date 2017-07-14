<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Class WildcardQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\TermLevel
 */
class WildcardQuery extends AbstractQuery
{

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

        return [
            'wildcard' => [
                $this->getField() => $this->getValue(),
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
