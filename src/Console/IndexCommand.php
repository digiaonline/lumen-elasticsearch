<?php namespace Nord\Lumen\Elasticsearch\Console;

use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction;
use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery;
use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkResponseAggregator;

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
        $bulkResponseAggregator = new BulkResponseAggregator();

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
                $response = $this->elasticsearchService->bulk($bulkQuery->toArray());
                $bulkQuery->reset();
                $bulkResponseAggregator->addResponse($response);
            }

            $bar->advance();
        }

        if ($bulkQuery->hasItems()) {
            $response = $this->elasticsearchService->bulk($bulkQuery->toArray());
            $bulkResponseAggregator->addResponse($response);
        }

        $bar->finish();

        $hasErrors = $bulkResponseAggregator->hasErrors();
        if($hasErrors) {
            $this->info("\n");
            $errors = $bulkResponseAggregator->getErrors();
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
}
