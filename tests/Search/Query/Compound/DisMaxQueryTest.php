<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

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
        $query      = $this->queryBuilder->createDisMaxQuery();
        $subQueries = [
            $this->queryBuilder->createBoolQuery(),
            $this->queryBuilder->createBoolQuery(),
        ];
        $query->setQueries($subQueries);

        $this->assertArrayHasKey('queries', $query->toArray());
        $this->assertCount(2, $query->toArray()['queries']);

        // Add a query
        $query->addQuery($this->queryBuilder->createBoolQuery());
        $this->assertCount(3, $query->toArray()['queries']);

        // Add tie breaker and boost
        $query->setTieBreaker(1.35);
        $this->assertEquals(1.35, $query->toArray()['tie_breaker']);

        $query->setBoost(3.14);
        $this->assertEquals(3.14, $query->toArray()['boost']);
    }
}
