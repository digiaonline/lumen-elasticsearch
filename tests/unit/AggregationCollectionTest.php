<?php

class AggregationCollectionTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\ElasticsearchService
     */
    protected $service;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationBuilder
     */
    protected $aggregationBuilder;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));
        $this->aggregationBuilder = $this->service->createAggregationBuilder();
    }

    /**
     * Tests collection.
     */
    public function testCollection()
    {
        $this->specify('adding aggregations to collection', function () {
            $collection = new \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection();
            verify($collection->count())->equals(0);

            $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));
            verify($collection->count())->equals(1);
        });


        $this->specify('getting aggregations from collection', function () {
            $collection = new \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection();
            $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));
            verify($collection->get(0)->getName())->equals('global_name');
        });


        $this->specify('iterating aggregation collection', function () {
            $collection = new \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection();
            $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));
            foreach ($collection as $aggregation) {
                /** @var \Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation $aggregation */
                verify($aggregation)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation');
                verify($aggregation->getName())->equals('global_name');
            }
        });


        $this->specify('removing aggregations from collection', function () {
            $collection = new \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection();
            verify($collection->count())->equals(0);

            $collection->add($this->aggregationBuilder->createGlobalAggregation()->setName('global_name'));
            verify($collection->count())->equals(1);

            $removed = $collection->remove(0);
            verify($removed)->notNull();
            verify($collection->count())->equals(0);

            $removed = $collection->remove(0);
            verify($removed)->null();
        });


        $this->specify('init collection with aggregations', function () {
            $collection = new \Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection([
                $this->aggregationBuilder->createGlobalAggregation()->setName('global_name')
            ]);
            verify($collection->count())->equals(1);
        });
    }
}
