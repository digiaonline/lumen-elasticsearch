<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class RangeQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class RangeQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = $this->queryBuilder->createRangeQuery();
        $query->setField('field')
              ->setGreaterThanOrEquals(10);

        $this->assertEquals([
            'range' => [
                'field' => [
                    'gte' => 10,
                ],
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createRangeQuery();
        $query->setField('field')
              ->setGreaterThan(10);

        $this->assertEquals([
            'range' => [
                'field' => [
                    'gt' => 10,
                ],
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createRangeQuery();
        $query->setField('field')
              ->setLessThanOrEquals(10);

        $this->assertEquals([
            'range' => [
                'field' => [
                    'lte' => 10,
                ],
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createRangeQuery();
        $query->setField('field')
              ->setLessThan(10);

        $this->assertEquals([
            'range' => [
                'field' => [
                    'lt' => 10,
                ],
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createRangeQuery();
        $query->setField('field')
              ->setBoost(2.0);

        $this->assertEquals([
            'range' => [
                'field' => [
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());
    }

}
