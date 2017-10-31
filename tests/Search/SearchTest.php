<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search;

use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\TermsAggregation;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
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

        $queryBuilder = $this->service->createQueryBuilder();

        $this->search = $this->service->createSearch();
        $this->query  = $queryBuilder->createBoolQuery();
        $this->query->addMust($queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));

        $sortBuilder = $this->service->createSortBuilder();
        $this->sort  = $this->service->createSort();
        $this->sort->addSort($sortBuilder->createScoreSort());

        $aggregationBuilder = $this->service->createAggregationBuilder();

        $this->aggregation = $aggregationBuilder->createGlobalAggregation();
        $this->aggregation->setName('global_name');
        $this->aggregation->addAggregation(
            $aggregationBuilder->createMinAggregation()->setField('field_name')->setName('min_name')
        );
        $this->aggregation->addAggregation(
            $aggregationBuilder->createMaxAggregation()->setField('field_name')->setName('max_name')
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
            'query' => ['match_all' => []],
            'size'  => 100,
            'from'  => 0,
        ],
            $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setPage(2);
        $this->search->setSize(100);

        $this->assertEquals([
            'query' => ['match_all' => []],
            'size'  => 100,
            'from'  => 100,
        ],
            $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setFrom(10);
        $this->search->setSize(10);

        $this->assertEquals([
            'query' => ['match_all' => []],
            'from'  => 10,
            'size'  => 10,
        ],
            $this->search->buildBody());

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
            'from'  => 0,
        ],
            $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->setSort($this->sort);

        $this->assertEquals([
            'query' => ['match_all' => []],
            'sort'  => ['_score'],
            'size'  => 100,
            'from'  => 0,
        ],
            $this->search->buildBody());

        $this->search = $this->service->createSearch();
        $this->search->addAggregation($this->aggregation);

        $this->assertEquals([
            'query' => ['match_all' => []],
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
            'from'  => 0,
        ],
            $this->search->buildBody());
    }

    /**
     * Test adding man array of aggregation to Search
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
            'query' => ['match_all' => []],
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
            'from'  => 0,
        ], $this->search->buildBody());
    }
}
