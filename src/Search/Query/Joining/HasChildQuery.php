<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
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
     * @inheritdoc
     */
    protected function getValidScoreModes()
    {
        return [
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_SUM,
            self::SCORE_MODE_MIN,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_NONE,
        ];
    }


    /**
     * @param int $minChildren
     * @return HasChildQuery
     */
    public function setMinChildren($minChildren)
    {
        $this->assertMinChildren($minChildren);
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
    public function setMaxChildren($maxChildren)
    {
        $this->assertMaxChildren($maxChildren);
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


    /**
     * @param int $minChildren
     * @throws InvalidArgument
     */
    protected function assertMinChildren($minChildren)
    {
        if (!is_int($minChildren)) {
            throw new InvalidArgument(sprintf(
                'HasChild Query `min_children` must be an integer, "%s" given.',
                gettype($minChildren)
            ));
        }
    }


    /**
     * @param int $maxChildren
     * @throws InvalidArgument
     */
    protected function assertMaxChildren($maxChildren)
    {
        if (!is_int($maxChildren)) {
            throw new InvalidArgument(sprintf(
                'HasChild Query `max_children` must be an integer, "%s" given.',
                gettype($maxChildren)
            ));
        }
    }
}
