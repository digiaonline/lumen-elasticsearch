<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\RegexpQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class RegexpQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class RegexpQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        // Basic value-only query
        $query = $this->queryBuilder->createRegexpQuery()->setField('foo')->setValue('bar[0-9]');

        $this->assertEquals([
            'regexp' => [
                'foo' => [
                    'value' => 'bar[0-9]',
                ],
            ],
        ], $query->toArray());

        // Value + boost
        $query->setBoost(2.0);

        $this->assertEquals([
            'regexp' => [
                'foo' => [
                    'value' => 'bar[0-9]',
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());

        // Value + boost + flags
        $query->setFlags([
            RegexpQuery::FLAG_COMPLEMENT,
            RegexpQuery::FLAG_INTERSECTION,
            RegexpQuery::FLAG_EMPTY,
        ]);

        $this->assertEquals([
            'regexp' => [
                'foo' => [
                    'value' => 'bar[0-9]',
                    'boost' => 2.0,
                    'flags' => 'COMPLEMENT|INTERSECTION|EMPTY',
                ],
            ],
        ], $query->toArray());

        // Value + boost + flags + max_determinized_states
        $query->setMaxDeterminizedStates(20000);

        $this->assertEquals([
            'regexp' => [
                'foo' => [
                    'value'                   => 'bar[0-9]',
                    'boost'                   => 2.0,
                    'flags'                   => 'COMPLEMENT|INTERSECTION|EMPTY',
                    'max_determinized_states' => 20000,
                ],
            ],
        ], $query->toArray());
    }

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument
     */
    public function testToArrayMissingFieldValue()
    {
        $this->queryBuilder->createRegexpQuery()->toArray();
    }
}
