<?php namespace Nord\Lumen\Elasticsearch\Queries;

/**
 * Elasticsearch provides a full Query DSL based on JSON to define queries.
 * Think of the Query DSL as an AST of queries, consisting of two types of clauses:
 *
 * - "Leaf query clauses"
 * Leaf query clauses look for a particular value in a particular field, such as the match, term or range queries.
 * These queries can be used by themselves.
 *
 * - "Compound query clauses"
 * Compound query clauses wrap other leaf or compound queries and are used to combine multiple queries in a logical
 * fashion (such as the bool or dis_max query), or to alter their behaviour (such as the not or constant_score query).
 *
 * Query clauses behave differently depending on whether they are used in query context or filter context.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html
 */
abstract class QueryDSL
{
    /**
     * @var int
     */
    private $size = 100;

    /**
     * @var int
     */
    private $page = 1;


    /**
     * @param int $size
     * @return QueryDSL
     */
    public function setSize($size)
    {
        $this->size = (int)$size;
        return $this;
    }


    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * @param int $page
     * @return QueryDSL
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }


    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * @return array
     */
    abstract function toArray();
}
