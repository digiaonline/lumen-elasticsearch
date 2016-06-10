<?php

class AggregationTest extends \Codeception\TestCase\Test
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
     * Tests the "Min" metrics aggregation
     */
    public function testMinAggregation()
    {
        $this->specify('min aggregation was created', function () {
            $aggregation = $this->aggregationBuilder->createMinAggregation();
            verify($aggregation)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MinAggregation');
        });

        $this->specify('max aggregation format', function () {
            $aggregation = $this->aggregationBuilder->createMinAggregation();
            $aggregation->setField('field_name')->setName('min_name');
            $array = $aggregation->toArray();
            verify($array)->equals(['min' => ['field' => 'field_name']]);
        });
    }

    /**
     * Tests the "Max" metrics aggregation
     */
    public function testMaxAggregation()
    {
        $this->specify('max aggregation was created', function () {
            $aggregation = $this->aggregationBuilder->createMaxAggregation();
            verify($aggregation)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Aggregation\Metrics\MaxAggregation');
        });

        $this->specify('max aggregation format', function () {
            $aggregation = $this->aggregationBuilder->createMaxAggregation();
            $aggregation->setField('field_name')->setName('max_name');
            $array = $aggregation->toArray();
            verify($array)->equals(['max' => ['field' => 'field_name']]);
        });
    }

    /**
     * Tests the "Global" bucket aggregation
     */
    public function testGlobalAggregation()
    {
        $this->specify('global aggregation was created', function () {
            $aggregation = $this->aggregationBuilder->createGlobalAggregation();
            verify($aggregation)->isInstanceOf('\Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket\GlobalAggregation');
        });


        $this->specify('global aggregation format', function () {
            $aggregation = $this->aggregationBuilder->createGlobalAggregation();
            $array = $aggregation->toArray();
            verify($array)->equals(['global' => new stdClass(), 'aggs' => []]);
        });


        $this->specify('global aggregation format with min/max aggregations', function () {
            $aggregation = $this->aggregationBuilder->createGlobalAggregation();
            $aggregation->addAggregation(
                $this->aggregationBuilder->createMinAggregation()->setField('field_name')->setName('min_name')
            );
            $aggregation->addAggregation(
                $this->aggregationBuilder->createMaxAggregation()->setField('field_name')->setName('max_name')
            );
            $array = $aggregation->toArray();
            verify($array)->equals([
                'global' => new stdClass(),
                'aggs' => [
                    'min_name' => ['min' => ['field' => 'field_name']],
                    'max_name' => ['max' => ['field' => 'field_name']],
                ]
            ]);
        });
    }
}
