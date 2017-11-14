<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Aggregation;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection;

/**
 * Class AggregationCollectionTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Aggregation
 */
class AggregationCollectionTest extends AbstractAggregationTestCase
{

    /**
     *
     */
    public function testConstructorAdd()
    {
        $aggregation = $this->aggregationBuilder->createGlobalAggregation()->setName('global_name');
        $collection  = new AggregationCollection([$aggregation, $aggregation]);

        $this->assertEquals(2, $collection->count());
    }

    /**
     *
     */
    public function testAdd()
    {
        $collection = new AggregationCollection();
        $this->assertEquals(0, $collection->count());

        $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));
        $this->assertEquals(1, $collection->count());
    }

    /**
     *
     */
    public function testGet()
    {
        $collection = new AggregationCollection();
        $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));

        $this->assertEquals('global_name', $collection->get(0)->getName());
    }

    /**
     *
     */
    public function testIterate()
    {
        $collection = new AggregationCollection();
        $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));

        foreach ($collection as $aggregation) {
            $this->assertInstanceOf(Aggregation::class, $aggregation);
            $this->assertEquals('global_name', $aggregation->getName());
        }
    }

    /**
     *
     */
    public function testRemove()
    {
        $collection = new AggregationCollection();
        $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));

        $this->assertInstanceOf(Aggregation::class, $collection->remove(0));
        $this->assertEquals(0, $collection->count());

        $this->assertNull($collection->remove(0));
    }
}
