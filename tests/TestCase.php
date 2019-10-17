<?php

namespace Nord\Lumen\Elasticsearch\Tests;

use Elasticsearch\ClientBuilder;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\ElasticsearchService;

/**
 * Class TestCase
 * @package Nord\Lumen\Elasticsearch\Tests
 */
class TestCase extends \PHPUnit_Framework_TestCase
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

        $this->service = new ElasticsearchService(ClientBuilder::fromConfig([]));
    }

    /**
     * @inheritDoc
     */
    protected function tearDown()
    {
        putenv('ELASTICSEARCH_INDEX_PREFIX=');
    }

    /**
     * @return string
     */
    protected function getResourcesBasePath()
    {
        return __DIR__ . '/Resources';
    }
}
