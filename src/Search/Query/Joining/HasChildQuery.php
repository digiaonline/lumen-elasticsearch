<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

/**
 * The has_child filter accepts a query and the child type to run against, and results in parent documents that have
 * child docs matching the query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-child-query.html
 */
class HasChildQuery extends AbstractQuery
{
    const SCORE_MODE_AVG  = 'avg';
    const SCORE_MODE_SUM  = 'sum';
    const SCORE_MODE_MIN  = 'min';
    const SCORE_MODE_MAX  = 'max';
    const SCORE_MODE_NONE = 'none';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $scoreMode;

    /**
     * @var int
     */
    private $minChildren;

    /**
     * @var int
     */
    private $maxChildren;

    /**
     * @var QueryDSL
     */
    private $query;


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
     * @param string $type
     * @return HasChildQuery
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string $scoreMode
     * @return HasChildQuery
     */
    public function setScoreMode($scoreMode)
    {
        $this->assertScoreMode($scoreMode);
        $this->scoreMode = $scoreMode;
        return $this;
    }


    /**
     * @return string
     */
    public function getScoreMode()
    {
        return $this->scoreMode;
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
     * @param QueryDSL $query
     * @return HasChildQuery
     */
    public function setQuery(QueryDSL $query)
    {
        $this->query = $query;
        return $this;
    }


    /**
     * @return QueryDSL
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * @param string $scoreMode
     * @throws InvalidArgument
     */
    protected function assertScoreMode($scoreMode)
    {
        $validModes = [
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_SUM,
            self::SCORE_MODE_MIN,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_NONE
        ];
        if (!in_array($scoreMode, $validModes)) {
            throw new InvalidArgument(sprintf(
                'HasChild Query `score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
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
