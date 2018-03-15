<?php namespace Nord\Lumen\Elasticsearch\Pagerfanta\Adapter;

use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Search\Search;
use Pagerfanta\Adapter\AdapterInterface;

class ElasticsearchAdapter implements AdapterInterface
{
    /**
     * @var ElasticsearchServiceContract
     */
    private $elasticsearch;

    /**
     * @var Search
     */
    private $search;

    /**
     * @var array
     */
    private $result;


    /**
     * @param ElasticsearchServiceContract $elasticsearch
     * @param Search $search
     */
    public function __construct(ElasticsearchServiceContract $elasticsearch, Search $search)
    {
        $this->elasticsearch = $elasticsearch;
        $this->search = $search;
    }


    /**
     * @inheritdoc
     */
    public function getNbResults()
    {
        $result = $this->getResult();
        return $result['hits']['total'] ?? 0;
    }


    /**
     * @inheritdoc
     */
    public function getSlice($offset, $length)
    {
        $result = $this->getResult($offset, $length);
        return $result['hits']['hits'] ?? [];
    }


    /**
     * @param int|null $offset
     * @param int|null $length
     * @return array
     */
    public function getResult($offset = null, $length = null)
    {
        if (null !== $offset && null !== $length) {
            $page = ($offset / $length) + 1;
            $size = $length;
            if ($page !== $this->search->getPage() || $size !== $this->search->getSize()) {
                $this->result = null;
                $this->search->setPage($page)
                    ->setSize($size);
            }
        }

        if (empty($this->result)) {
            $this->result = $this->elasticsearch->execute($this->search);
        }

        return $this->result;
    }


    /**
     * @return array
     */
    public function getAggregations()
    {
        $result = $this->getResult();
        return $result['aggregations'] ?? [];
    }
}
