<?php namespace Nord\Lumen\Elasticsearch\Pagerfanta\Adapter;

use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Queries\QueryDSL;
use Pagerfanta\Adapter\AdapterInterface;

class ElasticsearchAdapter implements AdapterInterface
{
    /**
     * @var ElasticsearchServiceContract
     */
    private $elasticsearch;

    /**
     * @var QueryDSL
     */
    private $query;

    /**
     * @var array
     */
    private $result;


    /**
     * @param ElasticsearchServiceContract $elasticsearch
     * @param QueryDSL $query
     */
    public function __construct(ElasticsearchServiceContract $elasticsearch, QueryDSL $query)
    {
        $this->elasticsearch = $elasticsearch;
        $this->query = $query;
    }


    /**
     * @inheritdoc
     */
    public function getNbResults()
    {
        $result = $this->getResult();
        return $result['hits']['total'];
    }


    /**
     * @inheritdoc
     */
    public function getSlice($offset, $length)
    {
        $result = $this->getResult($offset, $length);
        return $result['hits']['hits'];
    }


    /**
     * @param int|null $offset
     * @param int|null $length
     * @return array
     */
    public function getResult($offset = null, $length = null)
    {
        if (!is_null($offset) && !is_null($length)) {
            $page = ($offset / $length) + 1;
            $size = $length;
            if ($page !== $this->query->getPage() || $size !== $this->query->getSize()) {
                $this->result = null;
                $this->query->setPage($page)
                    ->setSize($size);
            }
        }

        if (empty($this->result)) {
            $this->result = $this->elasticsearch->execute($this->query);
        }

        return $this->result;
    }
}
