<?php namespace Nord\Lumen\Elasticsearch\Contracts;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Query;

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
    public function delete(array $params = []);


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
     * @return Query\Compound\BoolQuery
     */
    public function createBoolQuery();


    /**
     * @return Query\FullText\MatchQuery
     */
    public function createMatchQuery();


    /**
     * @return Query\TermLevel\RangeQuery
     */
    public function createRangeQuery();


    /**
     * @return Query\Geo\GeoDistanceQuery
     */
    public function createGeoDistanceQuery();


    /**
     * @param Query\QueryDSL $query
     * @return array
     */
    public function execute(Query\QueryDSL $query);


    /**
     * @param string $index
     * @return ElasticsearchServiceContract
     */
    public function changeIndex($index);


    /**
     * @param string $type
     * @return ElasticsearchServiceContract
     */
    public function changeType($type);
}
