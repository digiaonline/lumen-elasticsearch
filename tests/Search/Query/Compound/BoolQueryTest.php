<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

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
        $query = $this->queryBuilder->createBoolQuery();
        $query->addMust($this->queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));
        $query->addFilter($this->queryBuilder->createTermQuery()->setField('field2')->setValue('value2'));
        $query->addMustNot($this->queryBuilder->createRangeQuery()->setField('field3')->setGreaterThanOrEquals(1)
                                              ->setLessThanOrEquals(2));
        $query->addShould($this->queryBuilder->createTermQuery()->setField('field4')->setValue('value3'));
        $query->addShould($this->queryBuilder->createTermQuery()->setField('field4')->setValue('value4'));

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
    }

}
