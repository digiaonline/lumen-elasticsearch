<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Traits\HasTieBreaker;

/**
 * Class DisMaxQuery
 *
 * A query that generates the union of documents produced by its subqueries, and that scores each document with the
 * maximum score for that document as produced by any subquery, plus a tie breaking increment for any additional
 * matching subqueries.
 *
 * @package Nord\Lumen\Elasticsearch\Search\Query\Compound
 *
 * @see     https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-dis-max-query.html
 */
class DisMaxQuery extends AbstractQuery
{
    use HasBoost;
    use HasTieBreaker;

    /**
     * @var QueryDSL[]
     */
    private $queries = [];

    /**
     * @return QueryDSL[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param QueryDSL[] $queries
     *
     * @return $this
     */
    public function setQueries($queries)
    {
        $this->queries = $queries;

        return $this;
    }

    /**
     * @param QueryDSL $query
     *
     * @return $this
     */
    public function addQuery(QueryDSL $query)
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $body = [
            'queries' => array_map(function (QueryDSL $query) {
                return $query->toArray();
            }, $this->queries),
        ];

        if ($this->tieBreaker !== null) {
            $body['tie_breaker'] = $this->tieBreaker;
        }

        if ($this->hasBoost()) {
            $body['boost'] = $this->getBoost();
        }

        return $body;
    }
}
