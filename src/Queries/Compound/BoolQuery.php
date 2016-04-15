<?php namespace Nord\Lumen\Elasticsearch\Queries\Compound;

use Nord\Lumen\Elasticsearch\Queries\QueryDSL;

/**
 * A query that matches documents matching boolean combinations of other queries.
 *
 * The bool query maps to Lucene BooleanQuery. It is built using one or more boolean clauses,
 * each clause with a typed occurrence. The occurrence types are:
 *
 * - "must"
 * The clause (query) must appear in matching documents and will contribute to the score.
 *
 * - "filter"
 * The clause (query) must appear in matching documents. However unlike must the score of the query will be ignored.
 *
 * - "should"
 * The clause (query) should appear in the matching document. In a boolean query with no must or filter clauses, one or
 * more should clauses must match a document. The minimum number of should clauses to match can be set using the
 * minimum_should_match parameter.
 *
 * - "must_not"
 * The clause (query) must not appear in the matching documents.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html
 */
class BoolQuery extends AbstractQuery
{
    /**
     * @var QueryDSL[] The clause (query) must appear in matching documents and will contribute to the score.
     */
    private $must = [];

    /**
     * @var QueryDSL[] The clause (query) must appear in matching documents.
     * However unlike must the score of the query will be ignored.
     */
    private $filter = [];

    /**
     * @var QueryDSL[] The clause (query) should appear in the matching document.
     * In a boolean query with no must or filter clauses, one or more should clauses must match a document.
     * The minimum number of should clauses to match can be set using the minimum_should_match parameter.
     */
    private $should = [];

    /**
     * @var QueryDSL[] The clause (query) must not appear in the matching documents.
     */
    private $mustNot = [];


    /**
     * @param QueryDSL $query
     */
    public function addMust(QueryDSL $query)
    {
        $this->must[] = $query;
    }


    /**
     * @param QueryDSL $query
     */
    public function addFilter(QueryDSL $query)
    {
        $this->filter[] = $query;
    }


    /**
     * @param QueryDSL $query
     */
    public function addShould(QueryDSL $query)
    {
        $this->should[] = $query;
    }


    /**
     * @param QueryDSL $query
     */
    public function addMustNot(QueryDSL $query)
    {
        $this->mustNot[] = $query;
    }


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $array = [];

        if (!empty($this->must)) {
            $array['bool']['must'] = [];
            foreach ($this->must as $query) {
                $array['bool']['must'][] = $query->toArray();
            }
        }

        if (!empty($this->filter)) {
            $array['bool']['filter'] = [];
            foreach ($this->filter as $query) {
                $array['bool']['filter'][] = $query->toArray();
            }
        }

        if (!empty($this->should)) {
            $array['bool']['should'] = [];
            foreach ($this->should as $query) {
                $array['bool']['should'][] = $query->toArray();
            }
        }

        if (!empty($this->mustNot)) {
            $array['bool']['must_not'] = [];
            foreach ($this->mustNot as $query) {
                $array['bool']['must_not'][] = $query->toArray();
            }
        }

        return $array;
    }
}
