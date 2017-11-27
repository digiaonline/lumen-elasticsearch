<?php namespace Nord\Lumen\Elasticsearch\Contracts;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Parsers\SortStringParser;
use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationBuilder;
use Nord\Lumen\Elasticsearch\Search\Query\QueryBuilder;
use Nord\Lumen\Elasticsearch\Search\Search;
use Nord\Lumen\Elasticsearch\Search\Sort;

interface ElasticsearchServiceContract
{

    /**
     * @param array $params
     *
     * @return array
     */
    public function search(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function index(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function reindex(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function updateByQuery(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function bulk(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function delete(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function deleteByQuery(array $params = []);


    /**
     * @param array $params
     *
     * @return array
     */
    public function create(array $params = []);


    /**
     * @param array $params
     *
     * @return array|bool
     */
    public function exists(array $params = []);


    /**
     * @return IndicesNamespace
     */
    public function indices();


    /**
     * @return Search
     */
    public function createSearch();


    /**
     * @return Sort
     */
    public function createSort();


    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder();


    /**
     * @return Sort\SortBuilder
     */
    public function createSortBuilder();


    /**
     * @param array $config
     *
     * @return SortStringParser
     */
    public function createSortStringParser(array $config = []);


    /**
     * @return AggregationBuilder
     */
    public function createAggregationBuilder();


    /**
     * @param Search $search
     * @return array
     */
    public function execute(Search $search);
}
