<?php namespace Nord\Lumen\Elasticsearch\Console;

use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkAction;
use Nord\Lumen\Elasticsearch\Documents\Bulk\BulkQuery;

abstract class IndexCommand extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes data to an Elasticsearch index.';

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

        $bulkQuery = new BulkQuery($this->getBulkSize());

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
                $this->elasticsearchService->bulk($bulkQuery->toArray());
                $bulkQuery->reset();
            }

            $bar->advance();
        }

        if ($bulkQuery->hasItems()) {
            $this->elasticsearchService->bulk($bulkQuery->toArray());
        }

        $bar->finish();

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
     * Get the total count.
     *
     * @return int
     */
    protected function getCount()
    {
        return count($this->getData());
    }
}
