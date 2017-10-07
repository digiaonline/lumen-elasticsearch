<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query;

use Nord\Lumen\Elasticsearch\Search\Query\QueryBuilder;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractQueryTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query
 */
abstract class AbstractQueryTestCase extends TestCase
{

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

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

        $this->queryBuilder = $this->service->createQueryBuilder();
    }
}
