<?php namespace Nord\Lumen\Elasticsearch;

use Elasticsearch\Client;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

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
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $type;


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
    public function createBoolQuery()
    {
        return new Query\Compound\BoolQuery();
    }


    /**
     * @inheritdoc
     */
    public function createMatchQuery()
    {
        return new Query\FullText\MatchQuery();
    }


    /**
     * @inheritdoc
     */
    public function createRangeQuery()
    {
        return new Query\TermLevel\RangeQuery();
    }


    /**
     * @inheritdoc
     */
    public function createGeoDistanceQuery()
    {
        return new Query\Geo\GeoDistanceQuery();
    }


    /**
     * @inheritdoc
     */
    public function execute(Query\QueryDSL $query)
    {
        return $this->search([
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => $this->buildQueryBody($query),
        ]);
    }


    /**
     * @inheritdoc
     */
    public function changeIndex($index)
    {
        $this->index = $index;
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function changeType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }


    /**
     * @param Query\QueryDSL $query
     * @return array
     */
    private function buildQueryBody(Query\QueryDSL $query)
    {
        $body = [];

        $body['query'] = $query->toArray();
        if (empty($body['query'])) {
            $body['query'] = ['match_all' => []];
        }

        // Set how many results to return.
        if ($query->getSize() > 0) {
            $body['size'] = $query->getSize();
        }

        // Set which "page" of results to return.
        if ($query->getPage() > 0) {
            $body['from'] = isset($body['size']) ? ($query->getPage() * $body['size']) : 0;
        }

        return $body;
    }
}
