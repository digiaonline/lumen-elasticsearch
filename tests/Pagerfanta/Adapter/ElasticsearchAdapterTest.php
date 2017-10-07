<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pagerfanta\Adapter;

use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\ElasticsearchService;
use Nord\Lumen\Elasticsearch\Pagerfanta\Adapter\ElasticsearchAdapter;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermQuery;
use Nord\Lumen\Elasticsearch\Search\Search;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class ElasticsearchAdapterTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pagerfanta\Adapter
 */
class ElasticsearchAdapterTest extends TestCase
{

    /**
     * @var ElasticsearchService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $service;

    /**
     * @var BoolQuery
     */
    protected $query;

    /**
     * @var ElasticsearchAdapter
     */
    protected $adapter;

    /**
     * @var Search
     */
    protected $search;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->query = new BoolQuery();
        $termQuery   = new TermQuery();
        $this->query->addMust($termQuery->setField('field1')->setValue('value1'));

        $this->search = new Search();
        $this->search->setPage(1)->setSize(2)->setQuery($this->query);

        $this->adapter = new ElasticsearchAdapter($this->service, $this->search);
    }

    /**
     * Tests adapter total results.
     */
    public function testGetNbResults()
    {
        $this->service->expects($this->any())
                      ->method('execute')
                      ->with($this->search)
                      ->will($this->returnValue([
                          'hits' => [
                              'total' => 2,
                              'hits'  => [
                                  ['_source' => ['id' => 'd1', 'field1' => 'value1']],
                                  ['_source' => ['id' => 'd2', 'field1' => 'value1']],
                              ],
                          ],
                      ]));

        $this->assertEquals(2, $this->adapter->getNbResults());
    }

    /**
     * Tests adapter page 1 content.
     */
    public function testGetSlicePage1()
    {
        $this->service->expects($this->any())
                      ->method('execute')
                      ->with($this->search)
                      ->will($this->returnValue([
                          'hits' => [
                              'total' => 2,
                              'hits'  => [
                                  ['_source' => ['id' => 'd1', 'field1' => 'value1']],
                                  ['_source' => ['id' => 'd2', 'field1' => 'value1']],
                              ],
                          ],
                      ]));

        $this->assertEquals(2, $this->adapter->getNbResults());
        $this->assertEquals([
            ['_source' => ['id' => 'd1', 'field1' => 'value1']],
            ['_source' => ['id' => 'd2', 'field1' => 'value1']],
        ], $this->adapter->getSlice(0, 2));
    }

    /**
     * Tests adapter page 2 content.
     */
    public function testGetSlicePage2()
    {
        $this->service->expects($this->any())
                      ->method('execute')
                      ->with($this->search)
                      ->will($this->returnValue([
                          'hits' => [
                              'total' => 4,
                              'hits'  => [
                                  ['_source' => ['id' => 'd3', 'field1' => 'value1']],
                                  ['_source' => ['id' => 'd4', 'field1' => 'value1']],
                              ],
                          ],
                      ]));

        $this->assertEquals(4, $this->adapter->getNbResults());
        $this->assertEquals([
            ['_source' => ['id' => 'd3', 'field1' => 'value1']],
            ['_source' => ['id' => 'd4', 'field1' => 'value1']],
        ], $this->adapter->getSlice(2, 2));
    }
}
