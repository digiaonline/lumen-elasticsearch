<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\RangeQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermsQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class BoolQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Search\Query\Compound
 */
class BoolQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new BoolQuery();

        $query->addMust(new TermQuery('field1', 'value1'));
        $query->addFilter(new TermQuery('field2', 'value2'));
        $query->addMustNot((new RangeQuery())->setField('field3')->setGreaterThanOrEquals(1)->setLessThanOrEquals(2));
        $query->addShould(new TermQuery('field4', 'value3'));
        $query->addShould(new TermQuery('field4', 'value4'));

        $expectedArray = [
            'bool' => [
                'must'     => [
                    [
                        'term' => ['field1' => 'value1'],
                    ],
                ],
                'filter'   => [
                    [
                        'term' => ['field2' => 'value2'],
                    ],
                ],
                'must_not' => [
                    [
                        'range' => [
                            'field3' => [
                                'gte' => 1,
                                'lte' => 2,
                            ],
                        ],
                    ],
                ],
                'should'   => [
                    [
                        'term' => ['field4' => 'value3'],
                    ],
                    [
                        'term' => ['field4' => 'value4'],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $query->toArray());

        // Test empty query
        $query = new BoolQuery();

        $this->assertEquals([
            'bool' => new \stdClass(),
        ], $query->toArray());
    }

    /**
     * Test adding an array of filters to BoolQuery
     */
    public function testAddFilters()
    {
        $query = new BoolQuery();
        $query->addMust(new TermQuery('field1', 'value1'));
        $query->addFilter(new TermQuery('field2', 'value2'));

        $query->addFilters([
            (new TermsQuery())->setField('field3')->setValues(['value3']),
            (new TermsQuery())->setField('field4')->setValues(['value4']),
        ]);

        $expectedArray = [
            'bool' => [
                'must'     => [
                    [
                        'term' => ['field1' => 'value1'],
                    ],
                ],
                'filter'   => [
                    [
                        'term' => ['field2' => 'value2'],
                    ],
                    [
                        'terms' => [
                            'field3' => ['value3']
                        ],
                    ],
                    [
                        'terms' => [
                            'field4' => ['value4']
                        ],
                    ]
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $query->toArray());
    }
}
