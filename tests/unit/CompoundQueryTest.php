<?php

class CompoundQueryTest extends \Codeception\TestCase\Test
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
     * @var \Nord\Lumen\Elasticsearch\QueryBuilder
     */
    protected $queryBuilder;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));
        $this->queryBuilder = $this->service->createQueryBuilder();
    }


    /**
     * Tests the Bool Query.
     */
    public function testBoolQuery()
    {
        $this->specify('bool query was created', function () {
            $query = $this->queryBuilder->createBoolQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Queries\Compound\BoolQuery');
        });


        $this->specify('bool query with leaf queries ', function () {
            $query = $this->queryBuilder->createBoolQuery();
            $query->addMust($this->queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));
            $query->addFilter($this->queryBuilder->createTermQuery()->setField('field2')->setValue('value2'));
            $query->addMustNot($this->queryBuilder->createRangeQuery()->setField('field3')->setGreaterThanOrEquals(1)->setLessThanOrEquals(2));
            $query->addShould($this->queryBuilder->createTermQuery()->setField('field4')->setValue('value3'));
            $query->addShould($this->queryBuilder->createTermQuery()->setField('field4')->setValue('value4'));
            $array = $query->toArray();
            verify($array)->equals([
                'bool' => [
                    'must' => [
                        [
                            'term' => ['field1' => 'value1']
                        ]
                    ],
                    'filter' => [
                        [
                            'term' => ['field2' => 'value2']
                        ]
                    ],
                    'must_not' => [
                        [
                            'range' => [
                                'field3' => [
                                    'gte' => 1,
                                    'lte' => 2
                                ]
                            ]
                        ]
                    ],
                    'should' => [
                        [
                            'term' => ['field4' => 'value3']
                        ],
                        [
                            'term' => ['field4' => 'value4']
                        ]
                    ]
                ]
            ]);
        });
    }
}
