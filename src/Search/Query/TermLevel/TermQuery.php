<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;

/**
 * The term query finds documents that contain the exact term specified in the inverted index.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class TermQuery extends AbstractQuery
{
    use HasBoost;
    use HasValue;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $term = ['value' => $this->getValue()];

        $boost = $this->getBoost();
        if (null !== $boost) {
            $term['boost'] = $boost;
        }

        if (count($term) === 1 && isset($term['value'])) {
            $term = $term['value'];
        }

        return ['term' => [$this->getField() => $term]];
    }
}
