<?php

class BulkQueryTest extends \Codeception\TestCase\Test
{

    use \Codeception\Specify;

    const BULK_SIZE = 5;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery
     */
    private $query;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->query = new \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery(self::BULK_SIZE);
    }


    public function testActionHandling()
    {
        $this->specify('testing item handling', function () {
            verify($this->query->isReady())->false();

            for ($i = 0; $i < self::BULK_SIZE; $i++) {
                $this->query->addAction(new \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction());
            }

            verify($this->query->hasItems())->true();
            verify($this->query->isReady())->true();

            $this->query->reset();
            verify($this->query->hasItems())->false();
        });

    }


    public function testSerialization()
    {
        $this->specify('checking serialization', function () {
            $actionName = \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction::ACTION_INDEX;

            $metadata = [
                '_index' => 'foo',
                '_type'  => 'bar',
                '_id'    => 'baz',
            ];

            $body = [
                'foo' => 'bar',
            ];

            $parent = 'doc';

            // Add two actions (same item twice)
            $action = new \Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction();
            $action->setAction($actionName, $metadata)->setBody($body)->setParent($parent);

            $this->query->addAction($action)->addAction($action);

            verify($this->query->toArray())->equals([
                'body' => [
                    [
                        $actionName => $metadata,
                        $body,
                        'parent' => $parent
                    ],

                    [
                        $actionName => $metadata,
                        $body,
                        'parent' => $parent
                    ],

                ],
            ]);
        });
    }

}
