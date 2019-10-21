<?php namespace Nord\Lumen\Elasticsearch;

use Elasticsearch\Client;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Search\Search;
use Nord\Lumen\Elasticsearch\Search\Sort;

class ElasticsearchService implements ElasticsearchServiceContract
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string|null
     */
    private $indexPrefix;

    /**
     * ElasticsearchService constructor.
     *
     * @param Client      $client
     * @param string|null $indexPrefix
     */
    public function __construct(Client $client, ?string $indexPrefix = null)
    {
        $this->client      = $client;
        $this->indexPrefix = $indexPrefix;
    }

    /**
     * @inheritdoc
     */
    public function search(array $params = [])
    {
        return $this->client->search($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function index(array $params = [])
    {
        return $this->client->index($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function reindex(array $params = [])
    {
        // Index prefixing omitted on purpose here
        return $this->client->reindex($params);
    }

    /**
     * @inheritdoc
     */
    public function updateByQuery(array $params = [])
    {
        return $this->client->updateByQuery($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function bulk(array $params = [])
    {
        return $this->client->bulk($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function delete(array $params = [])
    {
        return $this->client->delete($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function deleteByQuery(array $params = [])
    {
        return $this->client->deleteByQuery($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function tasks()
    {
        return $this->client->tasks();
    }

    /**
     * @inheritdoc
     */
    public function create(array $params = [])
    {
        return $this->client->create($this->getPrefixedIndexParameters($params));
    }

    /**
     * @inheritdoc
     */
    public function exists(array $params = [])
    {
        return $this->client->exists($this->getPrefixedIndexParameters($params));
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
    public function execute(Search $search)
    {
        return $this->search([
            'index' => $this->getPrefixedIndexName($search->getIndex()),
            'type'  => $search->getType(),
            'body'  => $search->buildBody(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function count(Search $search): int
    {
        return $this->client->count([
            'index' => $this->getPrefixedIndexName($search->getIndex()),
            'type'  => $search->getType(),
            'body'  => $search->buildBody(),
        ])['count'];
    }

    /**
     * @inheritdoc
     */
    public function getPrefixedIndexName(string $indexName): string
    {
        if ($this->indexPrefix) {
            // Prefix every index
            return implode(',', array_map(function (string $indexName) {
                return sprintf('%s_%s', $this->indexPrefix, $indexName);
            }, explode(',', $indexName)));
        }

        return $indexName;
    }

    /**
     * @inheritdoc
     */
    public function getPrefixedIndexParameters(array $parameters): array
    {
        if (isset($parameters['index'])) {
            $parameters['index'] = $this->getPrefixedIndexName($parameters['index']);
        }

        return $parameters;
    }
}
