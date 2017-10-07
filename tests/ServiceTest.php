<?php

namespace Nord\Lumen\Elasticsearch\Tests;

use Elasticsearch\Client;
use Nord\Lumen\Elasticsearch\ElasticsearchService;

/**
 * Class ServiceTest
 * @package Nord\Lumen\Elasticsearch\Tests
 */
class ServiceTest extends TestCase
{

    /**
     * @var ElasticsearchService
     */
    protected $service;

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->client = $this->getMockBuilder(Client::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->service = new ElasticsearchService($this->client);
    }

    /**
     * Tests the search method.
     */
    public function testMethodSearch()
    {
        $input = [
            'index' => 'i',
            'type'  => 'd',
            'body'  => ['query' => ['match_all' => []]],
        ];

        $output = [
            'took' => 1,
            'hits' => [
                'total' => 0,
                'hits'  => [],
            ],
        ];

        $this->client->expects($this->any())
                     ->method('search')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->search($input));
    }

    /**
     * Tests the index method.
     */
    public function testMethodIndex()
    {
        $input = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'id'    => 'my_id',
            'body'  => ['field' => 'value'],
        ];

        $output = [
            '_index'   => 'my_index',
            '_type'    => 'my_type',
            '_id'      => 'my_id',
            '_version' => 1,
            'created'  => 1,
        ];

        $this->client->expects($this->any())
                     ->method('index')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->index($input));
    }

    /**
     * Tests the reindex method.
     */
    public function testMethodReindex()
    {
        $input = [
            'source' => [
                'index' => 'my_src_index',
            ],
            'dest'   => [
                'index' => 'my_dest_index',
            ],
        ];

        $output = [
            'took'                   => 3104,
            'timed_out'              => false,
            'total'                  => 270,
            'updated'                => 0,
            'created'                => 270,
            'batches'                => 1,
            'version_conflicts'      => 0,
            'noops'                  => 0,
            'retries'                => 0,
            'throttled_millis'       => 0,
            'requests_per_second'    => 'unlimited',
            'throttled_until_millis' => 0,
            'failures'               => [],
        ];

        $this->client->expects($this->any())
                     ->method('reindex')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->reindex($input));
    }

    public function testMethodUpdateByQuery()
    {
        $input = [];

        $output = [
            "took"                   => 5164,
            "timed_out"              => false,
            "total"                  => 270,
            "updated"                => 270,
            "batches"                => 1,
            "version_conflicts"      => 0,
            "noops"                  => 0,
            "retries"                => 0,
            "throttled_millis"       => 0,
            "requests_per_second"    => "unlimited",
            "throttled_until_millis" => 0,
            "failures"               => [],
        ];

        $this->client->expects($this->any())
                     ->method('updateByQuery')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->updateByQuery($input));
    }

    /**
     * Tests the bulk method.
     */
    public function testMethodBulk()
    {
        $input = [];

        $output = [];

        $this->client->expects($this->any())
                     ->method('bulk')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->bulk($input));
    }

    /**
     * Tests the delete method.
     */
    public function testMethodDelete()
    {
        $input = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'id'    => 'my_id',
        ];

        $output = [
            'found'    => 1,
            '_index'   => 'my_index',
            '_type'    => 'my_type',
            '_id'      => 'my_id',
            '_version' => 2,
        ];

        $this->client->expects($this->any())
                     ->method('delete')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->delete($input));
    }

    /**
     * Tests the create method.
     */
    public function testMethodCreate()
    {
        $input = [
            'index' => 'my_index',
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 2,
                    'number_of_replicas' => 0,
                ],
            ],
        ];

        $output = [
            'acknowledged' => 1,
        ];

        $this->client->expects($this->any())
                     ->method('create')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->create($input));
    }

    /**
     * Tests the exists method.
     */
    public function testMethodExists()
    {
        $input = [];

        $output = [];

        $this->client->expects($this->any())
                     ->method('exists')
                     ->with($input)
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->exists($input));
    }

    /**
     * Tests the indices method.
     */
    public function testMethodIndices()
    {
        $output = [];

        $this->client->expects($this->any())
                     ->method('indices')
                     ->will($this->returnValue($output));

        $this->assertEquals($output, $this->service->indices());
    }

}
