<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query;

use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractQueryTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query
 */
abstract class AbstractQueryTestCase extends TestCase
{

    /**
     *
     */
    abstract public function testToArray();
}
