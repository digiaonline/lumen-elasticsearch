<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MaxAggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MinAggregation;

class AggregationBuilder
{
    /**
     * @return GlobalAggregation
     */
    public function createGlobalAggregation()
    {
        return new GlobalAggregation();
    }


    /**
     * @return MaxAggregation
     */
    public function createMaxAggregation()
    {
        return new MaxAggregation();
    }


    /**
     * @return MinAggregation
     */
    public function createMinAggregation()
    {
        return new MinAggregation();
    }
}
