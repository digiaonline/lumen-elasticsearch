<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\DisMaxQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class DisMaxQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound
 */
class DisMaxQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        // Test with queries only
        $query      = new DisMaxQuery();
        $subQueries = [
            new BoolQuery(),
            new BoolQuery(),
        ];
        $query->setQueries($subQueries);

        $this->assertArrayHasKey('queries', $query->toArray()['dis_max']);
        $this->assertCount(2, $query->toArray()['dis_max']['queries']);
        $this->assertCount(2, $query->getQueries());

        // Add a query
        $query->addQuery(new BoolQuery());
        $this->assertCount(3, $query->toArray()['dis_max']['queries']);

        // Add tie breaker and boost
        $query->setTieBreaker(1.35);
        $this->assertEquals(1.35, $query->toArray()['dis_max']['tie_breaker']);

        $query->setBoost(3.14);
        $this->assertEquals(3.14, $query->toArray()['dis_max']['boost']);
    }
}
