<?php namespace Nord\Lumen\Elasticsearch\Contracts;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Queries;

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
     * @return Queries\Compound\BoolQuery
     */
    public function createBoolQuery();


    /**
     * @return Queries\FullText\MatchQuery
     */
    public function createMatchQuery();


    /**
     * @return Queries\TermLevel\TermQuery
     */
    public function createTermQuery();


    /**
     * @return Queries\TermLevel\RangeQuery
     */
    public function createRangeQuery();


    /**
     * @return Queries\Geo\GeoDistanceQuery
     */
    public function createGeoDistanceQuery();


    /**
     * @param Queries\QueryDSL $query
     * @return array
     */
    public function execute(Queries\QueryDSL $query);


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
