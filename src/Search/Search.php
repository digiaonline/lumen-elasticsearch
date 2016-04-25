<?php namespace Nord\Lumen\Elasticsearch\Search;

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
     * @var int
     */
    private $size = 100;

    /**
     * @var int
     */
    private $page = 1;


    /**
     * @param $index
     * @return Search
     */
    public function setIndex($index)
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
     * @param $type
     * @return Search
     */
    public function setType($type)
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

        if (($query = $this->getQuery()) !== null) {
            $body['query'] = $query->toArray();
        }
        if (empty($body['query'])) {
            $body['query'] = ['match_all' => []];
        }

        // Set how many results to return.
        if ($this->getSize() > 0) {
            $body['size'] = $this->getSize();
        }

        // Set which "page" of results to return.
        if ($this->getPage() > 0) {
            $page = $this->getPage() - 1;
            $body['from'] = isset($body['size']) ? ($page * $body['size']) : 0;
        }

        return $body;
    }
}
