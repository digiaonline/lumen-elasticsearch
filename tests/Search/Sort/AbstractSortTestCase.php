<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Sort;

use Nord\Lumen\Elasticsearch\Search\Sort\SortBuilder;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractSortTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Sort
 */
abstract class AbstractSortTestCase extends TestCase
{

    /**
     * @var SortBuilder
     */
    protected $sortBuilder;

    /**
     *
     */
    abstract public function testToArray();

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->sortBuilder = $this->service->createSortBuilder();
    }

}
