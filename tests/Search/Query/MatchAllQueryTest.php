<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query;

use Nord\Lumen\Elasticsearch\Search\Query\MatchAllQuery;

class MatchAllQueryTest extends AbstractQueryTestCase
{
    public function testToArray(): void
    {
        // With boost
        $query = new MatchAllQuery();
        $query->setBoost(1.2);

        $this->assertEquals([
            'match_all' => [
                'boost' => 1.2,
            ],
        ], $query->toArray());

        // Without boost
        $query = new MatchAllQuery();

        $this->assertEquals([
            'match_all' => new \stdClass(),
        ], $query->toArray());
    }
}
