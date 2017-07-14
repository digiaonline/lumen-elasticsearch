<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasQuery;

/**
 * Performing full SQL-style joins in a distributed system like Elasticsearch is prohibitively expensive.
 * Instead, Elasticsearch offers two forms of join which are designed to scale horizontally.
 *
 * - "nested" query
 * Documents may contains fields of type nested. These fields are used to index arrays of objects, where each object can
 * be queried (with the nested query) as an independent document.
 *
 * - "has_child" and "has_parent" queries
 * A parent-child relationship can exist between two document types within a single index. The has_child query returns
 * parent documents whose child documents match the specified query, while the has_parent query returns child documents
 * whose parent document matches the specified query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/joining-queries.html
 */
abstract class AbstractQuery extends QueryDSL
{
    use HasQuery;
    
    const SCORE_MODE_AVG   = 'avg';
    const SCORE_MODE_SUM   = 'sum';
    const SCORE_MODE_MIN   = 'min';
    const SCORE_MODE_MAX   = 'max';
    const SCORE_MODE_SCORE = 'score';
    const SCORE_MODE_NONE  = 'none';

    /**
     * @var string
     */
    protected $scoreMode;

    /**
     * @return array
     */
    abstract protected function getValidScoreModes();

    /**
     * @return string
     */
    public function getScoreMode()
    {
        return $this->scoreMode;
    }

    /**
     * @param string $scoreMode
     *
     * @return $this
     */
    public function setScoreMode($scoreMode)
    {
        $this->assertScoreMode($scoreMode);
        $this->scoreMode = $scoreMode;

        return $this;
    }

    /**
     * @param string $scoreMode
     *
     * @throws InvalidArgument
     */
    protected function assertScoreMode($scoreMode)
    {
        $validModes = $this->getValidScoreModes();

        if (!in_array($scoreMode, $validModes)) {
            throw new InvalidArgument(sprintf(
                '`score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
    }
}
