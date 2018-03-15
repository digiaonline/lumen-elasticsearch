<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasType;

/**
 * The has_child filter accepts a query and the child type to run against, and results in parent documents that have
 * child docs matching the query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-child-query.html
 */
class HasChildQuery extends AbstractQuery
{
    use HasType;

    /**
     * @var int
     */
    private $minChildren;

    /**
     * @var int
     */
    private $maxChildren;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $hasChild = [
            'type'  => $this->getType(),
            'query' => $this->getQuery()->toArray(),
        ];

        $scoreMode = $this->getScoreMode();
        if (!is_null($scoreMode)) {
            $hasChild['score_mode'] = $scoreMode;
        }

        $minChildren = $this->getMinChildren();
        if (!is_null($minChildren)) {
            $hasChild['min_children'] = $minChildren;
        }

        $maxChildren = $this->getMaxChildren();
        if (!is_null($maxChildren)) {
            $hasChild['max_children'] = $maxChildren;
        }

        return ['has_child' => $hasChild];
    }

    /**
     * @param int $minChildren
     * @return HasChildQuery
     */
    public function setMinChildren(int $minChildren)
    {
        $this->minChildren = $minChildren;
        return $this;
    }


    /**
     * @return int
     */
    public function getMinChildren()
    {
        return $this->minChildren;
    }


    /**
     * @param int $maxChildren
     * @return HasChildQuery
     */
    public function setMaxChildren(int $maxChildren)
    {
        $this->maxChildren = $maxChildren;
        return $this;
    }


    /**
     * @return int
     */
    public function getMaxChildren()
    {
        return $this->maxChildren;
    }
}
