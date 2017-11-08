<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class WildcardQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class WildcardQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        // Basic value-only query
        $query = $this->queryBuilder->createWildcardQuery()->setField('foo')->setValue('bar?baz*qux');

        $this->assertEquals([
            'wildcard' => [
                'foo' => [
                    'value' => 'bar?baz*qux',
                ],
            ],
        ], $query->toArray());

        // Value + boost
        $query->setBoost(2.0);

        $this->assertEquals([
            'wildcard' => [
                'foo' => [
                    'value' => 'bar?baz*qux',
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());
    }

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument
     */
    public function testToArrayMissingFieldValue()
    {
        $this->queryBuilder->createWildcardQuery()->toArray();
    }
}
