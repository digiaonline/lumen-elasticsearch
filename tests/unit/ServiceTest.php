<?php

class ServiceTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\ElasticsearchService
     */
    protected $service;

    /**
     * @var \Elasticsearch\Client
     */
    protected $client;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->client = $this->getMockBuilder('\Elasticsearch\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService($this->client);
    }


    /**
     * Tests the search method.
     */
    public function testMethodSearch()
    {
        $input = [
            'index' => 'i',
            'type'  => 'd',
            'body'  => ['query' => ['match_all' => []]]
        ];

        $output = [
            'took' => 1,
            'hits' => [
                'total' => 0,
                'hits'  => []
            ]
        ];

        $this->client->expects($this->any())
            ->method('search')
            ->with($input)
            ->will($this->returnValue($output));

        $this->specify('method search is ran', function () use ($input, $output) {
            verify($this->service->search($input))->equals($output);
        });
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
            'body'  => ['field' => 'value']
        ];

        $output = [
            '_index'   => 'my_index',
            '_type'    => 'my_type',
            '_id'      => 'my_id',
            '_version' => 1,
            'created'  => 1
        ];

        $this->client->expects($this->any())
            ->method('index')
            ->with($input)
            ->will($this->returnValue($output));

        $this->specify('method index is ran', function () use ($input, $output) {
            verify($this->service->index($input))->equals($output);
        });
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

        $this->specify('method bulk is ran', function () use ($input, $output) {
            verify($this->service->bulk($input))->equals($output);
        });
    }


    /**
     * Tests the delete method.
     */
    public function testMethodDelete()
    {
        $input = [
            'index' => 'my_index',
            'type'  => 'my_type',
            'id'    => 'my_id'
        ];

        $output = [
            'found'    => 1,
            '_index'   => 'my_index',
            '_type'    => 'my_type',
            '_id'      => 'my_id',
            '_version' => 2
        ];

        $this->client->expects($this->any())
            ->method('delete')
            ->with($input)
            ->will($this->returnValue($output));

        $this->specify('method delete is ran', function () use ($input, $output) {
            verify($this->service->delete($input))->equals($output);
        });
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
                    'number_of_replicas' => 0
                ]
            ]
        ];

        $output = [
            'acknowledged' => 1
        ];

        $this->client->expects($this->any())
            ->method('create')
            ->with($input)
            ->will($this->returnValue($output));

        $this->specify('method create is ran', function () use ($input, $output) {
            verify($this->service->create($input))->equals($output);
        });
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

        $this->specify('method exists is ran', function () use ($input, $output) {
            verify($this->service->exists($input))->equals($output);
        });
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

        $this->specify('method indices is ran', function () use ($output) {
            verify($this->service->indices())->equals($output);
        });
    }
}
