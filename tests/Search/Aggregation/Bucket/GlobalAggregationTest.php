<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Bucket;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MaxAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MinAggregation;
use Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\AbstractAggregationTestCase;

/**
 * Class GlobalAggregationTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Bucket
 */
class GlobalAggregationTest extends AbstractAggregationTestCase
{

    /**
     *
     */
    public function testToArray()
    {
        $aggregation = new GlobalAggregation();

        // Test on an empty aggregation
        $this->assertEquals([
            'global' => new \stdClass(),
            'aggs'   => [],
        ], $aggregation->toArray());

        // Test with some added aggregations
        $aggregation->addAggregation((new MinAggregation())->setField('field_name')->setName('min_name'));
        $aggregation->addAggregation((new MaxAggregation())->setField('field_name')->setName('max_name'));

        $this->assertEquals([
            'global' => new \stdClass(),
            'aggs'   => [
                'min_name' => ['min' => ['field' => 'field_name']],
                'max_name' => ['max' => ['field' => 'field_name']],
            ],
        ], $aggregation->toArray());
    }
}
