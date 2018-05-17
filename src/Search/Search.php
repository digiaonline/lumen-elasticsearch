<?php namespace Nord\Lumen\Elasticsearch\Search;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation;
use Nord\Lumen\Elasticsearch\Search\Aggregation\AggregationCollection;
use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

class Search
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $type;

    /**
     * @var QueryDSL
     */
    private $query;

    /**
     * @var array
     */
    private $source;

    /**
     * @var Sort
     */
    private $sort;

    /**
     * @var AggregationCollection
     */
    private $aggregations;

    /**
     * @var int
     */
    private $from = 0;

    /**
     * @var int
     */
    private $size = 100;

    /**
     * @var int
     */
    private $page = 1;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->aggregations = new AggregationCollection();
    }


    /**
     * @param string $index
     * @return Search
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
        return $this;
    }


    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }


    /**
     * @param string $type
     * @return Search
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param QueryDSL $query
     * @return Search
     */
    public function setQuery(QueryDSL $query)
    {
        $this->query = $query;
        return $this;
    }


    /**
     * @return QueryDSL
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * @param Sort $sort
     * @return Search
     */
    public function setSort(Sort $sort)
    {
        $this->sort = $sort;
        return $this;
    }


    /**
     * @return Sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $source
     *
     * @return $this
     */
    public function setSource(array $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
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
     * @return Search
     */
    public function addAggregation(Aggregation $aggregation)
    {
        $this->aggregations->add($aggregation);
        return $this;
    }


    /**
     * @param array $aggregations
     * @return Search
     */
    public function addAggregations(array $aggregations)
    {
        foreach ($aggregations as $aggregation) {
            if ($aggregation instanceof Aggregation) {
                $this->addAggregation($aggregation);
            }
        }
        return $this;
    }


    /**
     * @param int $page
     * @return Search
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param int $from
     *
     * @return Search
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }


    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * @param int $size
     * @return Search
     */
    public function setSize($size)
    {
        $this->size = (int)$size;
        return $this;
    }


    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * @return array
     */
    public function buildBody()
    {
        $body = [];
        $query = $this->getQuery();
            
        if ($query !== null) {
            $body['query'] = $query->toArray();
        }
            
        if (empty($body['query'])) {
            $body['query'] = ['match_all' => new \stdClass()];
        }

        $sort = $this->getSort();
            
        if ($sort !== null) {
            $body['sort'] = $sort->toArray();
        }

        if (!empty($this->getSource())) {
            $body['_source'] = $this->getSource();
        }

        $aggregations = $this->getAggregations();
        if ($aggregations->count() > 0) {
            $body['aggs'] = $aggregations->toArray();
        }

        // Set how many results to return.
        if ($this->getSize() > 0) {
            $body['size'] = $this->getSize();
        }

        // Use "from" to determine "from, if it's not set we determine it from the "page"
        if ($this->getFrom() > 0) {
            $from = $this->getFrom();
        } else {
            $from = ($this->getPage() - 1) * $this->getSize();
        }

        $body['from'] = $from;

        return $body;
    }
}
