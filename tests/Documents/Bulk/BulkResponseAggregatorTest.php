<?php

namespace Nord\Lumen\Elasticsearch\Tests\Documents\Bulk;

use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkResponseAggregator;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class BulkResponseAggregatorTest
 * @package Nord\Lumen\Elasticsearch\Tests\Documents\Bulk
 */
class BulkResponseAggregatorTest extends TestCase
{

    /**
     * @var \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkResponseAggregator
     */
    protected $aggregator;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->aggregator = new BulkResponseAggregator();
    }

    /**
     *
     */
    public function testAddResponse()
    {
        $this->assertFalse($this->aggregator->hasErrors());

        $response = [
            'type' => [
                'items' => [
                    '_index' => 'foo',
                    '_type'  => 'bar',
                    '_id'    => 'baz',
                    'error'  =>  [
                        'type'   => 'err type',
                        'reason' => 'err reason',
                        'caused_by' => [
                            'type'   => 'err cause type',
                            'reason' => 'err cuase reason'
                        ]
                    ],
                ]
            ]
        ];

        $this->aggregator->addResponse($response);

        $this->assertCount(1, $this->aggregator->getErrors());
        
        $this->aggregator->reset();

        $this->assertFalse($this->aggregator->hasErrors());
    }
}
