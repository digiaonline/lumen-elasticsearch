<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValues;

/**
 * Filters documents that have fields that match any of the provided terms (not analyzed).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class TermsQuery extends AbstractQuery
{
    use HasValues;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'terms' => [
                $this->getField() => $this->getValues()
            ]
        ];
    }
}
