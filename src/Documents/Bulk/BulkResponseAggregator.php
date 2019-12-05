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
        $items = $response['items'] ?? [];

        foreach ($items as $item) {
            $item = $item['type'];

            // Ignore items without errors
            $error = $item['error'] ?? null;

            if ($error === null) {
                continue;
            }

            // Ignore errors without caused_by
            $causedBy = $error['caused_by'] ?? null;

            if ($causedBy === null) {
                continue;
            }

            $this->errors[] = sprintf('Error "%s" reason "%s". Cause "%s" reason "%s". Index "%s", type "%s", id "%s"',
                $error['type'], $error['reason'], $causedBy['type'], $causedBy['reason'], $item['_index'],
                $item['_type'], $item['_id']);
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
