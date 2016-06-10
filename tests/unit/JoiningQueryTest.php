<?php

class JoiningQueryTest extends \Codeception\TestCase\Test
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
     * @var \Nord\Lumen\Elasticsearch\Search\Query\QueryBuilder
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
     * Tests the Nested Query.
     */
    public function testNestedQuery()
    {
        $this->specify('nested query was created', function () {
            $query = $this->queryBuilder->createNestedQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Query\Joining\NestedQuery');
        });


        $this->specify('nested query format', function () {
            $query = $this->queryBuilder->createNestedQuery();
            $query->setPath('doc')
                ->setQuery(
                    $this->queryBuilder
                        ->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createMatchQuery()
                                ->setField('field')
                                ->setValue('value')
                        )
                );
            $array = $query->toArray();
            verify($array)->equals([
                'nested' => [
                    'path' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['match' => ['field' => 'value']]
                            ]
                        ]
                    ],
                ]
            ]);
        });

        $this->specify('nested query format with score_mode', function () {
            $query = $this->queryBuilder->createNestedQuery();
            $query->setPath('doc')
                ->setQuery(
                    $this->queryBuilder
                        ->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createMatchQuery()
                                ->setField('field')
                                ->setValue('value')
                        )
                )
                ->setScoreMode(\Nord\Lumen\Elasticsearch\Search\Query\Joining\NestedQuery::SCORE_MODE_AVG);
            $array = $query->toArray();
            verify($array)->equals([
                'nested' => [
                    'path' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['match' => ['field' => 'value']]
                            ]
                        ]
                    ],
                    'score_mode' => 'avg',
                ]
            ]);
        });
    }


    /**
     * Tests the HasParent Query.
     */
    public function testHasParentQuery()
    {
        $this->specify('hasParent query was created', function () {
            $query = $this->queryBuilder->createHasParentQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Query\Joining\HasParentQuery');
        });

        $this->specify('hasParent query format', function () {
            $query = $this->queryBuilder->createHasParentQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                );
            $array = $query->toArray();
            verify($array)->equals([
                'has_parent' => [
                    'parent_type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                ]
            ]);
        });

        $this->specify('hasParent query format with score_mode', function () {
            $query = $this->queryBuilder->createHasParentQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                )
                ->setScoreMode(\Nord\Lumen\Elasticsearch\Search\Query\Joining\HasParentQuery::SCORE_MODE_SCORE);
            $array = $query->toArray();
            verify($array)->equals([
                'has_parent' => [
                    'parent_type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                    'score_mode' => 'score',
                ]
            ]);
        });
    }


    /**
     * Tests the HasChild Query.
     */
    public function testHasChildQuery()
    {
        $this->specify('hasChild query was created', function () {
            $query = $this->queryBuilder->createHasChildQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Query\Joining\HasChildQuery');
        });

        $this->specify('hasChild query format', function () {
            $query = $this->queryBuilder->createHasChildQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                );
            $array = $query->toArray();
            verify($array)->equals([
                'has_child' => [
                    'type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                ]
            ]);
        });

        $this->specify('hasChild query format with score_mode', function () {
            $query = $this->queryBuilder->createHasChildQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                )
                ->setScoreMode(\Nord\Lumen\Elasticsearch\Search\Query\Joining\HasChildQuery::SCORE_MODE_SUM);
            $array = $query->toArray();
            verify($array)->equals([
                'has_child' => [
                    'type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                    'score_mode' => 'sum',
                ]
            ]);
        });

        $this->specify('hasChild query format with min_children', function () {
            $query = $this->queryBuilder->createHasChildQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                )
                ->setMinChildren(2);
            $array = $query->toArray();
            verify($array)->equals([
                'has_child' => [
                    'type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                    'min_children' => 2,
                ]
            ]);
        });

        $this->specify('hasChild query format with max_children', function () {
            $query = $this->queryBuilder->createHasChildQuery();
            $query->setType('doc')
                ->setQuery(
                    $this->queryBuilder->createBoolQuery()
                        ->addMust(
                            $this->queryBuilder->createTermsQuery()
                                ->setField('id')
                                ->setValues(['ID1', 'ID2'])
                        )
                )
                ->setMaxChildren(10);
            $array = $query->toArray();
            verify($array)->equals([
                'has_child' => [
                    'type' => 'doc',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['id' => ['ID1', 'ID2']]]
                            ]
                        ]
                    ],
                    'max_children' => 10,
                ]
            ]);
        });
    }
}
