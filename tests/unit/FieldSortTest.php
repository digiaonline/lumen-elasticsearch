<?php

class FieldSortTest extends \Codeception\TestCase\Test
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
     * Tests the Field sort format.
     */
    public function testSortFormat()
    {
        $this->specify('field sort was created', function () {
            $sort = $this->sortBuilder->createFieldSort();
            verify($sort)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Sort\FieldSort');
        });


        $this->specify('field sort format', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field');
            $array = $sort->toArray();
            verify($array)->equals('field');
        });


        $this->specify('field sort format with order', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field')->setOrder('asc');
            $array = $sort->toArray();
            verify($array)->equals(['field' => ['order' => 'asc']]);
        });


        $this->specify('field sort format with mode', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field')->setMode('avg');
            $array = $sort->toArray();
            verify($array)->equals(['field' => ['mode' => 'avg']]);
        });


        $this->specify('field sort format with order and mode', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field')->setOrder('asc')->setMode('avg');
            $array = $sort->toArray();
            verify($array)->equals(['field' => ['order' => 'asc', 'mode' => 'avg']]);
        });


        $this->specify('field sort format with missing', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field')->setMissing('_last');
            $array = $sort->toArray();
            verify($array)->equals(['field' => ['missing' => '_last']]);
        });


        $this->specify('field sort format with unmapped_type', function () {
            $sort = $this->sortBuilder->createFieldSort();
            $sort->setField('field')->setUnmappedType('long');
            $array = $sort->toArray();
            verify($array)->equals(['field' => ['unmapped_type' => 'long']]);
        });
    }
}
