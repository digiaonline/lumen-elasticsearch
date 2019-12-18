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
            'items' => [
                // An index operation with an error
                [
                    'index' => [
                        '_index' => 'foo',
                        '_type'  => 'bar',
                        '_id'    => 'baz',
                        'error'  => [
                            'type'      => 'err type',
                            'reason'    => 'err reason',
                            'caused_by' => [
                                'type'   => 'err cause type',
                                'reason' => 'err cuase reason',
                            ],
                        ],
                    ],
                ],
                // A delete operation without an error
                [
                    'delete' => [
                        '_index' => 'foo',
                        '_type'  => 'bar',
                        '_id'    => 'baz',
                    ],
                ],
                // A create operation with an error, but no caused_by
                [
                    'create' => [
                        '_index' => 'foo',
                        '_type'  => 'bar',
                        '_id'    => 'baz',
                        'error'  => [
                            'type'   => 'err type',
                            'reason' => 'err reason',
                        ],
                    ],
                ],
            ],
        ];

        $this->aggregator->addResponse($response);

        // Only the item with a full error (including caused_by) should be added
        $this->assertCount(1, $this->aggregator->getErrors());

        $this->aggregator->reset();

        $this->assertFalse($this->aggregator->hasErrors());
    }
}
