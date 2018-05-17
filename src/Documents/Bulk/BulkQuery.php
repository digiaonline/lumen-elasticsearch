<?php namespace Nord\Lumen\Elasticsearch\Documents\Bulk;

class BulkQuery
{

    /**
     * The number of actions to handle in one query
     */
    public const BULK_SIZE_DEFAULT = 500;

    /**
     * @var BulkAction[]
     */
    private $actions = [];

    /**
     * @var int
     */
    private $bulkSize;


    /**
     * BulkQuery constructor.
     *
     * @param int $bulkSize
     */
    public function __construct($bulkSize)
    {
        $this->bulkSize = $bulkSize;
    }


    /**
     * @param BulkAction $action
     *
     * @return BulkQuery
     */
    public function addAction(BulkAction $action)
    {
        $this->actions[] = $action;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasItems()
    {
        return !empty($this->actions);
    }


    /**
     * @return bool whether the query is ready for dispatch
     */
    public function isReady()
    {
        return count($this->actions) === $this->bulkSize;
    }


    /**
     * Removes all actions
     */
    public function reset()
    {
        $this->actions = [];
    }


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $result = [
            'body' => [],
        ];

        foreach ($this->actions as $i => $action) {
            $result['body'][] = [
                $action->getAction() => $action->getMetadata()
            ];
            $result['body'][] = $action->getBody();
        }

        return $result;
    }
}
