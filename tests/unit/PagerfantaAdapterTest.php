<?php

class PagerfantaAdapterTest extends \Codeception\TestCase\Test
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
     * @var \Nord\Lumen\Elasticsearch\Queries\Compound\BoolQuery()
     */
    protected $query;

    /**
     * @var \Nord\Lumen\Elasticsearch\Pagerfanta\Adapter\ElasticsearchAdapter
     */
    protected $adapter;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->service = $this->getMockBuilder('\Nord\Lumen\Elasticsearch\ElasticsearchService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->query = new \Nord\Lumen\Elasticsearch\Queries\Compound\BoolQuery();
        $termQuery = new \Nord\Lumen\Elasticsearch\Queries\TermLevel\TermQuery();
        $this->query->addMust($termQuery->setField('field1')->setValue('value1'));
        $this->query->setPage(1)->setSize(2);

        $this->adapter = new \Nord\Lumen\Elasticsearch\Pagerfanta\Adapter\ElasticsearchAdapter($this->service, $this->query);
    }


    /**
     * Tests adapter total results.
     */
    public function testGetNbResults()
    {
        $this->service->expects($this->any())
            ->method('execute')
            ->with($this->query)
            ->will($this->returnValue([
                'hits' => [
                    'total' => 2,
                    'hits' => [
                        ['_source' => ['id' =>'d1', 'field1' => 'value1']],
                        ['_source' => ['id' =>'d2', 'field1' => 'value1']]
                    ]
                ]
            ]));

        $this->specify('getting total results', function () {
            verify($this->adapter->getNbResults())->equals(2);
        });
    }


    /**
     * Tests adapter page 1 content.
     */
    public function testGetSlicePage1()
    {
        $this->service->expects($this->any())
            ->method('execute')
            ->with($this->query)
            ->will($this->returnValue([
                'hits' => [
                    'total' => 2,
                    'hits' => [
                        ['_source' => ['id' =>'d1', 'field1' => 'value1']],
                        ['_source' => ['id' =>'d2', 'field1' => 'value1']]
                    ]
                ]
            ]));

        $this->specify('getting page 1 results', function () {
            verify($this->adapter->getSlice(0, 2))->equals([
                ['_source' => ['id' =>'d1', 'field1' => 'value1']],
                ['_source' => ['id' =>'d2', 'field1' => 'value1']]
            ]);
            verify($this->adapter->getNbResults())->equals(2);
        });



    }


    /**
     * Tests adapter page 2 content.
     */
    public function testGetSlicePage2()
    {
        $this->service->expects($this->any())
            ->method('execute')
            ->with($this->query)
            ->will($this->returnValue([
                'hits' => [
                    'total' => 4,
                    'hits' => [
                        ['_source' => ['id' =>'d3', 'field1' => 'value1']],
                        ['_source' => ['id' =>'d4', 'field1' => 'value1']]
                    ]
                ]
            ]));

        $this->specify('getting page 2 results', function () {
            verify($this->adapter->getSlice(1, 2))->equals([
                ['_source' => ['id' =>'d3', 'field1' => 'value1']],
                ['_source' => ['id' =>'d4', 'field1' => 'value1']]
            ]);
            verify($this->adapter->getNbResults())->equals(4);
        });
    }
}
