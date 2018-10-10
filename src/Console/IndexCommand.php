<?php namespace Nord\Lumen\Elasticsearch\Console;

use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction;
use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery;

abstract class IndexCommand extends AbstractCommand
{

    /**
     * The number of items to process before updating the progress bar
     */
    const PROGRESS_BAR_REDRAW_FREQUENCY = 50;

    /**
     * @return array
     */
    abstract public function getData();

    /**
     * @return string
     */
    abstract public function getIndex();

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @param mixed $item
     *
     * @return array
     */
    abstract public function getItemBody($item);

    /**
     * @param mixed $item
     *
     * @return string
     */
    abstract public function getItemId($item);

    /**
     * @param mixed $item
     *
     * @return string
     */
    abstract public function getItemParent($item);

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->info(sprintf('Indexing data of type "%s" into "%s"', $this->getType(), $this->getIndex()));

        $data = $this->getData();

        $bar = $this->output->createProgressBar($this->getCount());
        $bar->setRedrawFrequency($this->getProgressBarRedrawFrequency());

        $bulkQuery = new BulkQuery($this->getBulkSize());
        $responses = [];

        foreach ($data as $item) {
            $action = new BulkAction();

            $meta = [
                '_index' => $this->getIndex(),
                '_type'  => $this->getType(),
                '_id'    => $this->getItemId($item),
            ];

            if (($parent = $this->getItemParent($item)) !== null) {
                $meta['_parent'] = $parent;
            }

            $action->setAction(BulkAction::ACTION_INDEX, $meta)
                   ->setBody($this->getItemBody($item));

            $bulkQuery->addAction($action);

            if ($bulkQuery->isReady()) {
                $responses[] = $this->elasticsearchService->bulk($bulkQuery->toArray());
                $bulkQuery->reset();
            }

            $bar->advance();
        }

        if ($bulkQuery->hasItems()) {
            $responses[] = $this->elasticsearchService->bulk($bulkQuery->toArray());
        }

        $bar->finish();

        $errors = $this->getErrors($responses);
        if($errors) {
            $this->info("\n");
            foreach($errors as $error) {
                $this->error($error);
            }
        }

        $this->info("\nDone!");

        return 0;
    }

    /**
     * @return int the bulk size (for bulk indexing)
     */
    protected function getBulkSize()
    {
        return BulkQuery::BULK_SIZE_DEFAULT;
    }

    /**
     * @return int the progress bar redraw frequency
     */
    protected function getProgressBarRedrawFrequency()
    {
        return self::PROGRESS_BAR_REDRAW_FREQUENCY;
    }

    /**
     * Get the total count.
     *
     * @return int
     */
    protected function getCount()
    {
        return count($this->getData());
    }

    /**
     * @param array $responses
     * @return array
     */
    protected function getErrors($responses) 
    {
        $errors = [];
        foreach($responses as $type => $response) {

            $items = array_get($response, 'items', []);
            foreach($items as $item) {

                $item = array_first($item);

                if(!array_has($item, 'error')) {
                    continue;
                }

                $index = array_get($item, '_index');
                $type = array_get($item, '_type');
                $id = array_get($item, '_id');

                $errorType = array_get($item, 'error.type');
                $errorReason = array_get($item, 'error.reason');

                $causeType = array_get($item, 'error.caused_by.type');
                $causeReason = array_get($item, 'error.caused_by.reason');

                $errors[] = sprintf('Error "%s" reason "%s". Cause "%s" reason "%s". Index "%s", type "%s", id "%s"', 
                    $errorType, $errorReason, $causeType, $causeReason, $index, $type, $id);
            }
        }
        return $errors;
    }
}
