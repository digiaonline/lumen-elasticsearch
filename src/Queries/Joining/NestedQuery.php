<?php namespace Nord\Lumen\Elasticsearch\Queries\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Queries\QueryDSL;

/**
 * Nested query allows to query nested objects / docs (see nested mapping).
 *
 * The query is executed against the nested objects / docs as if they were indexed as separate docs (they are,
 * internally) and resulting in the root parent doc (or parent nested mapping).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html
 */
class NestedQuery extends AbstractQuery
{
    const SCORE_MODE_AVG  = 'avg';
    const SCORE_MODE_SUM  = 'sum';
    const SCORE_MODE_MIN  = 'min';
    const SCORE_MODE_MAX  = 'max';
    const SCORE_MODE_NONE = 'none';

    /**
     * @var string
     */
    private $path;

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
        $nested = [
            'path'  => $this->getPath(),
            'query' => $this->getQuery()->toArray(),
        ];

        $scoreMode = $this->getScoreMode();
        if (!is_null($scoreMode)) {
            $nested['score_mode'] = $scoreMode;
        }

        return ['nested' => $nested];
    }


    /**
     * @param string $path
     * @return NestedQuery
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * @param string $scoreMode
     * @return NestedQuery
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
     * @return NestedQuery
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
                'Nested Query `score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
    }
}
