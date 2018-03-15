<?php namespace Nord\Lumen\Elasticsearch\Documents\Bulk;

class BulkAction
{
    public const ACTION_INDEX = 'index';

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @var array
     */
    private $body;


    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * @param string $action
     * @param array  $metadata
     * @return BulkAction
     */
    public function setAction($action, array $metadata)
    {
        $this->action   = $action;
        $this->metadata = $metadata;
        return $this;
    }


    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }


    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * @param array $body
     * @return BulkAction
     */
    public function setBody(array $body)
    {
        $this->body = $body;
        return $this;
    }
}
