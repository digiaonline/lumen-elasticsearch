<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\ExistsQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class ExistsQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class ExistsQueryTest extends AbstractQueryTestCase
{

    /**
     *
     */
    public function testToArray()
    {
        $query = new ExistsQuery();
        $query->setField('foo');

        $this->assertEquals([
            'exists' => ['field' => 'foo'],
        ], $query->toArray());
    }
}
