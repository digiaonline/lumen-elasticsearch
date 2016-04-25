<?php namespace Nord\Lumen\Elasticsearch\Contracts;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\QueryBuilder;
use Nord\Lumen\Elasticsearch\Search;

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
     * @return Search
     */
    public function createSearch();


    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder();


    /**
     * @param Search $search
     * @return array
     */
    public function execute(Search $search);
}
