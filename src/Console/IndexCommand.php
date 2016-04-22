<?php namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Queries\Bulk\BulkAction;
use Nord\Lumen\Elasticsearch\Queries\Bulk\BulkQuery;

abstract class IndexCommand extends Command
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
     * @param array $item
     *
     * @return array
     */
    abstract public function getItemBody($item);

    /**
     * @param $item
     *
     * @return string
     */
    abstract public function getItemId($item);


    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->info('Indexing data ...');

        $service = $this->getElasticsearchService();

        $data = $this->getData();

        $bar = $this->output->createProgressBar(count($data));

        $bulkQuery = new BulkQuery($this->getBulkSize());

        foreach ($data as $item) {
            // Create an action for the item
            $action = new BulkAction();

            $action->setAction(BulkAction::ACTION_INDEX, [
                '_index' => $this->getIndex(),
                '_type'  => $this->getType(),
                '_id'    => $this->getItemId($item),
            ])->setBody($this->getItemBody($item));

            // Add it to the bulk query
            $bulkQuery->addAction($action);

            // Flush and reset when ready
            if ($bulkQuery->isReady()) {
                $service->bulk($bulkQuery->toArray());
                $bulkQuery->reset();
            }

            $bar->advance();
        }

        // Flush remaining items
        if ($bulkQuery->hasItems()) {
            $service->bulk($bulkQuery->toArray());
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
     * @return ElasticsearchServiceContract
     */
    private function getElasticsearchService()
    {
        return app(ElasticsearchServiceContract::class);
    }
}
