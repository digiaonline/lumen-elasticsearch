<?php

namespace Nord\Lumen\Elasticsearch\Tests;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\ElasticsearchService;

/**
 * Class TestCase
 * @package Nord\Lumen\Elasticsearch\Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @var ElasticsearchServiceContract
     */
    protected $service;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = new ElasticsearchService($this->createDummyClient());
    }

    /**
     * @return Client
     */
    protected function createDummyClient(): Client
    {
        return ClientBuilder::fromConfig([]);
    }

    /**
     * @return string
     */
    protected function getResourcesBasePath()
    {
        return __DIR__ . '/Resources';
    }
}
