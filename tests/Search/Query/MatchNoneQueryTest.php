<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query;

use Nord\Lumen\Elasticsearch\Search\Query\MatchNoneQuery;

class MatchNoneQueryTest extends AbstractQueryTestCase
{
    public function testToArray(): void
    {
        $query = new MatchNoneQuery();

        $this->assertEquals([
            'match_none' => new \stdClass(),
        ], $query->toArray());
    }
}
