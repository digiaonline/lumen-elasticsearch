<?php

class ScoreSortTest extends \Codeception\TestCase\Test
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
     * @var \Nord\Lumen\Elasticsearch\Search\Sort\SortBuilder
     */
    protected $sortBuilder;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));
        $this->sortBuilder = $this->service->createSortBuilder();
    }


    /**
     * Tests the Score sort format.
     */
    public function testSortFormat()
    {
        $this->specify('score sort was created', function () {
            $sort = $this->sortBuilder->createScoreSort();
            verify($sort)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Sort\ScoreSort');
        });


        $this->specify('score sort format', function () {
            $sort = $this->sortBuilder->createScoreSort();
            $array = $sort->toArray();
            verify($array)->equals('_score');
        });


        $this->specify('score sort format with order', function () {
            $sort = $this->sortBuilder->createScoreSort();
            $sort->setOrder('asc');
            $array = $sort->toArray();
            verify($array)->equals(['_score' => ['order' => 'asc']]);
        });
    }
}
