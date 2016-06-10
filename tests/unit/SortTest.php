<?php

class SortTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Sort
     */
    protected $sort;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Sort\SortBuilder
     */
    protected $sortBuilder;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        $service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));

        $this->sortBuilder = $service->createSortBuilder();
        $this->sort = $service->createSort();
    }


    /**
     * Tests setters & getters.
     */
    public function testSetterGetter()
    {
        $this->specify('sorts can be set and get', function () {
            $this->sort->setSorts([
                $this->sortBuilder->createFieldSort()->setField('f'),
                $this->sortBuilder->createScoreSort(),
            ]);
            verify($this->sort->getSorts())->count(2);
        });


        $this->specify('sort can be added and get', function () {
            $this->sort->addSort($this->sortBuilder->createScoreSort());
            verify($this->sort->getSorts())->count(1);
        });
    }
}
