<?php

namespace Nord\Lumen\Elasticsearch\Tests\Documents\Bulk;

use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction;
use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class BulkQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Documents\Bulk
 */
class BulkQueryTest extends TestCase
{

    const BULK_SIZE = 5;

    /**
     * @var \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery
     */
    protected $query;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->query = new BulkQuery(self::BULK_SIZE);
    }

    /**
     *
     */
    public function testAddAction()
    {
        $this->assertFalse($this->query->isReady());

        for ($i = 0; $i < self::BULK_SIZE; $i++) {
            $this->query->addAction(new BulkAction());
        }

        $this->assertTrue($this->query->isReady());
        $this->assertTrue($this->query->hasItems());

        $this->query->reset();
        $this->assertFalse($this->query->hasItems());
        $this->assertFalse($this->query->isReady());
    }

    /**
     *
     */
    public function testNormalSerialization()
    {
        $actionName = BulkAction::ACTION_INDEX;

        $metadata = [
            '_index' => 'foo',
            '_type'  => 'bar',
            '_id'    => 'baz',
        ];

        $body = [
            'foo' => 'bar',
        ];

        // Add two actions (same item twice)
        $action = new BulkAction();
        $action->setAction($actionName, $metadata)->setBody($body);

        $this->query->addAction($action)->addAction($action);

        $this->assertEquals([
            'body' => [
                [$actionName => $metadata],
                $body,
                [$actionName => $metadata],
                $body,
            ],
        ], $this->query->toArray());
    }

    /**
     *
     */
    public function testParentSerialization()
    {
        $actionName = BulkAction::ACTION_INDEX;

        $metadata = [
            '_index'  => 'foo',
            '_type'   => 'bar',
            '_id'     => 'baz',
            '_parent' => 'qux',
        ];

        $body = [
            'foo' => 'bar',
        ];

        // Add two actions (same item twice)
        $action = new BulkAction();
        $action->setAction($actionName, $metadata)->setBody($body);

        $this->query->addAction($action)->addAction($action);

        $this->assertEquals([
            'body' => [
                [$actionName => $metadata],
                $body,
                [$actionName => $metadata],
                $body,
            ],
        ], $this->query->toArray());
    }

}
