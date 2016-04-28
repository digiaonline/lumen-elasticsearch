<?php

class DocSortTest extends \Codeception\TestCase\Test
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
     * Tests the Doc sort format.
     */
    public function testSortFormat()
    {
        $this->specify('doc sort was created', function () {
            $sort = $this->sortBuilder->createDocSort();
            verify($sort)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Sort\DocSort');
        });


        $this->specify('doc sort format', function () {
            $sort = $this->sortBuilder->createDocSort();
            $array = $sort->toArray();
            verify($array)->equals('_doc');
        });


        $this->specify('doc sort format with order', function () {
            $sort = $this->sortBuilder->createDocSort();
            $sort->setOrder('asc');
            $array = $sort->toArray();
            verify($array)->equals(['_doc' => ['order' => 'asc']]);
        });
    }
}
