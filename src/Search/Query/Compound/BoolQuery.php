<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

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
     * @return BoolQuery
     */
    public function addMust(QueryDSL $query)
    {
        $this->must[] = $query;
        return $this;
    }


    /**
     * @param QueryDSL $query
     * @return BoolQuery
     */
    public function addFilter(QueryDSL $query)
    {
        $this->filter[] = $query;
        return $this;
    }

    /**
     * @param array $filters
     *
     * @return BoolQuery
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            if ($filter instanceof QueryDSL) {
                $this->addFilter($filter);
            }
        }
        return $this;
    }


    /**
     * @param QueryDSL $query
     * @return BoolQuery
     */
    public function addShould(QueryDSL $query)
    {
        $this->should[] = $query;
        return $this;
    }


    /**
     * @param QueryDSL $query
     * @return BoolQuery
     */
    public function addMustNot(QueryDSL $query)
    {
        $this->mustNot[] = $query;
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $array = [];

        $fields = [
            'must'    => 'must',
            'filter'  => 'filter',
            'should'  => 'should',
            'mustNot' => 'must_not',
        ];

        foreach ($fields as $propertyName => $fieldName) {
            $property = $this->{$propertyName};

            if (!empty($property)) {
                $array['bool'][$fieldName] = [];
                foreach ($property as $query) {
                    /** @var QueryDSL $query */
                    $array['bool'][$fieldName][] = $query->toArray();
                }
            }
        }

        return $array;
    }
}
