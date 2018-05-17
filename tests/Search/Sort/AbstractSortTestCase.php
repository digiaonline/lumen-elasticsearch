<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Sort;

use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractSortTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Sort
 */
abstract class AbstractSortTestCase extends TestCase
{

    /**
     *
     */
    abstract public function testToArray();
}
