<?php namespace Nord\Lumen\Elasticsearch\Queries\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Queries\QueryDSL;

/**
 * The has_parent query accepts a query and a parent type. The query is executed in the parent document space, which is
 * specified by the parent type. This query returns child documents which associated parents have matched. For the rest
 * has_parent query has the same options and works in the same manner as the has_child query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-parent-query.html
 */
class HasParentQuery extends AbstractQuery
{
    const SCORE_MODE_SCORE  = 'score';
    const SCORE_MODE_NONE   = 'none';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $scoreMode;

    /**
     * @var QueryDSL
     */
    private $query;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $hasParent = [
            'parent_type'  => $this->getType(),
            'query'        => $this->getQuery()->toArray(),
        ];

        $scoreMode = $this->getScoreMode();
        if (!is_null($scoreMode)) {
            $hasParent['score_mode'] = $scoreMode;
        }

        return ['has_parent' => $hasParent];
    }


    /**
     * @param string $type
     * @return HasParentQuery
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
     * @return HasParentQuery
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
     * @param QueryDSL $query
     * @return HasParentQuery
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
            self::SCORE_MODE_SCORE,
            self::SCORE_MODE_NONE
        ];
        if (!in_array($scoreMode, $validModes)) {
            throw new InvalidArgument(sprintf(
                'HasParent Query `score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
    }
}
