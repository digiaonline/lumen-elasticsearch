<?php namespace Nord\Lumen\Elasticsearch\Queries\Bulk;

class BulkAction
{

    const ACTION_INDEX = 'index';

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
     */
    public function setAction($action, $metadata)
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
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

}
