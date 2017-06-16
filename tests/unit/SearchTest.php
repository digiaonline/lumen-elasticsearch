<?php

class SearchTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Search
     */
    protected $search;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery
     */
    protected $query;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Sort
     */
    protected $sort;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation
     */
    protected $aggregation;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));
        $queryBuilder = $service->createQueryBuilder();

        $this->search = $service->createSearch();
        $this->query = $queryBuilder->createBoolQuery();
        $this->query->addMust($queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));

        $sortBuilder = $service->createSortBuilder();
        $this->sort = $service->createSort();
        $this->sort->addSort($sortBuilder->createScoreSort());

        $aggregationBuilder = $service->createAggregationBuilder();

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
     * Tests setters & getters.
     */
    public function testSetterGetter()
    {
        $this->specify('index can be set and get', function () {
            $this->search->setIndex('index');
            verify($this->search->getIndex())->equals('index');
        });


        $this->specify('type can be set and get', function () {
            $this->search->setType('doc');
            verify($this->search->getType())->equals('doc');
        });


        $this->specify('query can be set and get', function () {
            $this->search->setQuery($this->query);
            verify($this->search->getQuery())->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery');
        });


        $this->specify('page can be set and get', function () {
            $this->search->setPage(1);
            verify($this->search->getPage())->equals(1);
        });

        $this->specify('from can be set and get', function () {
            $this->search->setFrom(10);
            verify($this->search->getFrom())->equals(10);
        });

        $this->specify('size can be set and get', function () {
            $this->search->setSize(100);
            verify($this->search->getSize())->equals(100);
        });


        $this->specify('sort can be set and get', function () {
            $this->search->setSort($this->sort);
            verify($this->search->getSort())->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Sort');
        });


        $this->specify('aggregation can be added and collection retrieved', function () {
            $this->search->addAggregation($this->aggregation);
            $collection = $this->search->getAggregations();
            verify($collection)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection');
        });
    }


    /**
     * Tests building the elasticsearch query body.
     */
    public function testBuildBody()
    {
        $this->specify('match all query body page 1', function () {
            $this->search->setPage(1);
            $this->search->setSize(100);
            verify($this->search->buildBody())->equals([
                'query' => ['match_all' => []],
                'size'  => 100,
                'from'  => 0,
            ]);
        });


        $this->specify('match all query body page 2', function () {
            $this->search->setPage(2);
            $this->search->setSize(100);
            verify($this->search->buildBody())->equals([
                'query' => ['match_all' => []],
                'size'  => 100,
                'from'  => 100,
            ]);
        });

        $this->specify('match all query body from and size', function () {
            $this->search->setFrom(10);
            $this->search->setSize(10);
            verify($this->search->buildBody())->equals([
                'query' => ['match_all' => []],
                'from'  => 10,
                'size'  => 10,
            ]);
        });

        $this->specify('bool query body page 1', function () {
            $this->search->setPage(1);
            $this->search->setSize(100);
            $this->search->setQuery($this->query);
            verify($this->search->buildBody())->equals([
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => ['field1' => 'value1']
                            ]
                        ],
                    ]
                ],
                'size'  => 100,
                'from'  => 0,
            ]);
        });


        $this->specify('match all query with sort body', function () {
            $this->search->setSort($this->sort);
            verify($this->search->buildBody())->equals([
                'query' => ['match_all' => []],
                'sort'  => ['_score'],
                'size'  => 100,
                'from'  => 0,
            ]);
        });


        $this->specify('match all query with global aggregation', function () {
            $this->search->addAggregation($this->aggregation);
            verify($this->search->buildBody())->equals([
                'query' => ['match_all' => []],
                'aggs'  => [
                    'global_name' => [
                        'global' => new stdClass(),
                        'aggs' => [
                            'min_name' => ['min' => ['field' => 'field_name']],
                            'max_name' => ['max' => ['field' => 'field_name']],
                        ]
                    ]
                ],
                'size'  => 100,
                'from'  => 0,
            ]);
        });
    }
}
