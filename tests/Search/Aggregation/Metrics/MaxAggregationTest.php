<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Metrics;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MaxAggregation;
use Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\AbstractAggregationTestCase;

/**
 * Class MaxAggregationTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Metrics
 */
class MaxAggregationTest extends AbstractAggregationTestCase
{

    /**
     *
     */
    public function testToArray()
    {
        $aggregation = new MaxAggregation();

        $aggregation->setField('field_name');
        $this->assertEquals([
            'max' => ['field' => 'field_name'],
        ], $aggregation->toArray());
    }
}
