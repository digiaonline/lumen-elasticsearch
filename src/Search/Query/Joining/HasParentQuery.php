<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasType;

/**
 * The has_parent query accepts a query and a parent type. The query is executed in the parent document space, which is
 * specified by the parent type. This query returns child documents which associated parents have matched. For the rest
 * has_parent query has the same options and works in the same manner as the has_child query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-parent-query.html
 */
class HasParentQuery extends AbstractQuery
{
    use HasType;

    /**
     * @inheritdoc
     * @throws InvalidArgument
     */
    public function toArray()
    {
        $query = $this->getQuery();

        if ($query === null) {
            throw new InvalidArgument('Query must be set');
        }
        
        $hasParent = [
            'parent_type'  => $this->getType(),
            'query'        => $query->toArray(),
        ];

        $scoreMode = $this->getScoreMode();
        if (null !== $scoreMode) {
            $hasParent['score_mode'] = $scoreMode;
        }

        return ['has_parent' => $hasParent];
    }
}
