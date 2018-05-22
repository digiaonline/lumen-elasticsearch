<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search;

use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\TermsAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MaxAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MinAggregation;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermQuery;
use Nord\Lumen\Elasticsearch\Search\Search;
use Nord\Lumen\Elasticsearch\Search\Sort;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class SearchTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search
 */
class SearchTest extends TestCase
{

    /**
     * @var Search
     */
    protected $search;

    /**
     * @var BoolQuery
     */
    protected $query;

    /**
     * @var Sort
     */
    protected $sort;

    /**
     * @var GlobalAggregation
     */
    protected $aggregation;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->search = $this->service->createSearch();
        $this->query  = new BoolQuery();
        $this->query->addMust(new TermQuery('field1', 'value1'));

        $this->sort  = $this->service->createSort();
        $this->sort->addSort(new Sort\ScoreSort());

        $this->aggregation = new GlobalAggregation();
        $this->aggregation->setName('global_name');
        $this->aggregation->addAggregation(
            (new MinAggregation())->setField('field_name')->setName('min_name')
        );
        $this->aggregation->addAggregation(
            (new MaxAggregation())->setField('field_name')->setName('max_name')
        );
    }

    /**
     *
     */
    public function testSetterGetter()
    {
        $this->search->setIndex('index');
        $this->assertEquals('index', $this->search->getIndex());

        $this->search->setType('doc');
        $this->assertEquals('doc', $this->search->getType());

        $this->search->setQuery($this->query);
        $this->assertInstanceOf(BoolQuery::class, $this->search->getQuery());

        $this->search->setPage(1);
        $this->assertEquals(1, $this->search->getPage());

        $this->search->setSize(100);
        $this->assertEquals(100, $this->search->getSize());

        $this->search->setSource(['*']);
        $this->assertEquals(['*'], $this->search->getSource());

        $this->search->setSort($this->sort);
        $this->assertInstanceOf(Sort::class, $this->search->getSort());

        $this->search->addAggregation($this->aggregation);
        $this->assertInstanceOf(AggregationCollection::class, $this->search->getAggregations());
    }

    public function testToArray()
    {
        $this->search = $this->service->createSearch();
        $this->search->setPage(1);
        $this->search->setSize(100);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'size'  => 100,
        ], $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setPage(2);
        $this->search->setSize(100);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'size'  => 100,
            'from'  => 100,
        ], $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setFrom(10);
        $this->search->setSize(10);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'from'  => 10,
            'size'  => 10,
        ], $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setPage(1);
        $this->search->setSize(100);
        $this->search->setQuery($this->query);

        $this->assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => ['field1' => 'value1'],
                        ],
                    ],
                ],
            ],
            'size'  => 100,
        ], $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setSort($this->sort);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'sort'  => ['_score'],
            'size'  => 100,
        ], $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->addAggregation($this->aggregation);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'aggs'  => [
                'global_name' => [
                    'global' => new \stdClass(),
                    'aggs'   => [
                        'min_name' => ['min' => ['field' => 'field_name']],
                        'max_name' => ['max' => ['field' => 'field_name']],
                    ],
                ],
            ],
            'size'  => 100,
        ], $this->search->buildBody());
        
        // Make sure "from" is present when it's a positive number
        $this->search->setFrom(10);

        $this->assertArraySubset([
            'from' => 10,
        ], $this->search->buildBody());
        
        // Make sure "size" is not present when it's set to zero
        $this->search->setSize(0);
        
        $this->assertArrayNotHasKey('size', $this->search->buildBody());
    }

    public function testToArrayWithSource()
    {
        $this->search = $this->service->createSearch();
        $this->search->setPage(1);
        $this->search->setSize(100);
        $this->search->setSource(['id', 'title', 'description']);

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'size' => 100,
            '_source' => [
                'id',
                'title',
                'description'
            ],
        ], $this->search->buildBody());
    }


    /**
     * Test adding an array of aggregation to Search
     */
    public function testAddAggregations()
    {
        $this->search = $this->service->createSearch();
        $this->search->setPage(1);
        $this->search->setSize(100);

        $aggregations = [
            (new TermsAggregation())->setName('name1')->setField('field1'),
            (new TermsAggregation())->setName('name2')->setField('field2')
        ];

        $this->search->addAggregations($aggregations);

        $this->assertInstanceOf(AggregationCollection::class, $this->search->getAggregations());

        $this->assertEquals([
            'query' => ['match_all' => new \stdClass()],
            'aggs'  => [
                'name1' => [
                    'terms' => [
                        'field' => 'field1'
                    ]
                ],
                'name2' => [
                    'terms' => [
                        'field' => 'field2'
                    ]
                ]
            ],
            'size'  => 100,
        ], $this->search->buildBody());
    }
}
