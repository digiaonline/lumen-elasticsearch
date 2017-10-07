<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation;

use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationBuilder;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractAggregationTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Aggregation
 */
abstract class AbstractAggregationTestCase extends TestCase
{

    /**
     * @var AggregationBuilder
     */
    protected $aggregationBuilder;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->aggregationBuilder = $this->service->createAggregationBuilder();
    }

}
