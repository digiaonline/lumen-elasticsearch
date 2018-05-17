<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;

/**
 * Class WildcardQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\TermLevel
 *
 * @see     https://www.elastic.co/guide/en/elasticsearch/reference/2.4/query-dsl-wildcard-query.html
 */
class WildcardQuery extends AbstractQuery
{
    use HasBoost;
    use HasValue;

    /**
     * @inheritdoc
     *
     * @throws InvalidArgument
     */
    public function toArray()
    {
        if ($this->field === null || $this->value === null) {
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
}
