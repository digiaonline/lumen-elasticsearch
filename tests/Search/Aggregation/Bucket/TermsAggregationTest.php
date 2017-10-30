<?php namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\Bucket;

use Nord\Lumen\Elasticsearch\Tests\Search\Aggregation\AbstractAggregationTestCase;

class TermsAggregationTest extends AbstractAggregationTestCase
{
    public function testToArray()
    {
        $aggregation = $this->aggregationBuilder->createTermsAggregation();

        $aggregation->setField('field_name');

        $this->assertEquals([
            'terms' => ['field' => 'field_name'],
        ], $aggregation->toArray());
    }
}
