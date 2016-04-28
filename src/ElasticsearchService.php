<?php namespace Nord\Lumen\Elasticsearch;

use Elasticsearch\Client;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Search\Query\QueryBuilder;
use Nord\Lumen\Elasticsearch\Search\Search;
use Nord\Lumen\Elasticsearch\Search\Sort;

class ElasticsearchService implements ElasticsearchServiceContract
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $settings;


    /**
     * ElasticsearchService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @inheritdoc
     */
    public function search(array $params = [])
    {
        return $this->client->search($params);
    }


    /**
     * @inheritdoc
     */
    public function index(array $params = [])
    {
        return $this->client->index($params);
    }


    /**
     * @inheritdoc
     */
    public function bulk(array $params = [])
    {
        return $this->client->bulk($params);
    }


    /**
     * @inheritdoc
     */
    public function delete(array $params = [])
    {
        return $this->client->delete($params);
    }


    /**
     * @inheritdoc
     */
    public function create(array $params = [])
    {
        return $this->client->create($params);
    }


    /**
     * @inheritdoc
     */
    public function exists(array $params = [])
    {
        return $this->client->exists($params);
    }


    /**
     * @inheritdoc
     */
    public function indices()
    {
        return $this->client->indices();
    }


    /**
     * @inheritdoc
     */
    public function createSearch()
    {
        return new Search();
    }


    /**
     * @inheritdoc
     */
    public function createSort()
    {
        return new Sort();
    }


    /**
     * @inheritdoc
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder();
    }


    /**
     * @inheritdoc
     */
    public function createSortBuilder()
    {
        return new Sort\SortBuilder();
    }


    /**
     * @inheritdoc
     */
    public function execute(Search $search)
    {
        return $this->search([
            'index' => $search->getIndex(),
            'type'  => $search->getType(),
            'body'  => $search->buildBody(),
        ]);
    }


    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
