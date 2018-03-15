<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation;

class AggregationCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var Aggregation[]
     */
    private $aggregations = [];


    /**
     * @param array $aggregations
     */
    public function __construct(array $aggregations = [])
    {
        foreach ($aggregations as $aggregation) {
            $this->add($aggregation);
        }
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->aggregations as $aggregation) {
            $array[$aggregation->getName()] = $aggregation->toArray();
        }

        return $array;
    }


    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->aggregations);
    }


    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->aggregations);
    }


    /**
     * @param Aggregation $aggregation
     * @return AggregationCollection
     */
    public function add(Aggregation $aggregation)
    {
        $this->aggregations[] = $aggregation;
        return $this;
    }


    /**
     * @param int $index
     * @return Aggregation|null
     */
    public function remove($index)
    {
        if (!isset($this->aggregations[$index])) {
            return null;
        }

        $removed = $this->aggregations[$index];
        unset($this->aggregations[$index]);

        return $removed;
    }


    /**
     * @param int $index
     * @return Aggregation|null
     */
    public function get($index)
    {
        return $this->aggregations[$index] ?? null;
    }
}
