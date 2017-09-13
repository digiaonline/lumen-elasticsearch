<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

/**
 * Returns documents that have at least one non-null value in the original field.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
 */
class ExistsQuery extends AbstractQuery
{

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return ['exists' => ['field' => $this->getField()]];
    }
}
