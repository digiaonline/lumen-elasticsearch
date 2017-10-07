<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Metrics;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MinAggregation;
use Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\AbstractAggregationTestCase;

/**
 * Class MinAggregationTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Metrics
 */
class MinAggregationTest extends AbstractAggregationTestCase
{

    /**
     *
     */
    public function testToArray()
    {
        $aggregation = $this->aggregationBuilder->createMinAggregation();

        $aggregation->setField('field_name');
        $this->assertEquals([
            'min' => ['field' => 'field_name'],
        ], $aggregation->toArray());
    }

}
