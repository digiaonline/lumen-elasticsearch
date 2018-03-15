<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\ConstantScoreQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class ConstantScoreQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound
 */
class ConstantScoreQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new BoolQuery();
        $query->addMust($this->queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));

        $constantScoreQuery = new ConstantScoreQuery();
        $constantScoreQuery->setQuery($query)
                           ->setBoost(5.0);

        $this->assertEquals([
            'constant_score' => [
                'filter' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => ['field1' => 'value1'],
                            ],
                        ],
                    ],
                ],
                'boost'  => 5.0,
            ],
        ], $constantScoreQuery->toArray());
    }

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument
     */
    public function testToArrayWithMissingQuery()
    {
        (new ConstantScoreQuery())->toArray();
    }
}
