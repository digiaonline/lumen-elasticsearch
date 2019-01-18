<?php namespace Nord\Lumen\Elasticsearch\Documents\Bulk;

class BulkResponseAggregator
{

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param array $response
     *
     * @return BulkResponseAggregator
     */
    public function addResponse(array $response)
    {
        $this->parseErrors($response);

        return $this;
    }


    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }


    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $response
     */
    protected function parseErrors(array $response)
    {
        $items = array_get($response, 'items', []);
        foreach ($items as $item) {
            $item = array_first($item);

            if (!array_has($item, 'error')) {
                continue;
            }

            $index = array_get($item, '_index');
            $type = array_get($item, '_type');
            $id = array_get($item, '_id');

            $errorType = array_get($item, 'error.type');
            $errorReason = array_get($item, 'error.reason');

            $causeType = array_get($item, 'error.caused_by.type');
            $causeReason = array_get($item, 'error.caused_by.reason');

            $this->errors[] = sprintf('Error "%s" reason "%s". Cause "%s" reason "%s". Index "%s", type "%s", id "%s"',
                $errorType, $errorReason, $causeType, $causeReason, $index, $type, $id);
        }
    }


    /**
     *
     */
    public function reset()
    {
        $this->errors = [];
    }
}
