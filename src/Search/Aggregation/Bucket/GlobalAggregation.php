<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection;

/**
 * Defines a single bucket of all the documents within the search execution context. This context is defined by the
 * indices and the document types you’re searching on, but is not influenced by the search query itself.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-global-aggregation.html
 */
class GlobalAggregation extends AbstractAggregation
{
    /**
     * @var AggregationCollection
     */
    private $aggregations;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->aggregations = new AggregationCollection();
    }


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'global' => new \stdClass(),
            'aggs' => $this->getAggregations()->toArray()
        ];
    }


    /**
     * @return AggregationCollection
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }


    /**
     * @param Aggregation $aggregation
     * @return GlobalAggregation
     */
    public function addAggregation(Aggregation $aggregation)
    {
        $this->aggregations->add($aggregation);
        return $this;
    }
}
