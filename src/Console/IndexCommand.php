<?php namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

abstract class IndexCommand extends Command
{

    /**
     * The number of items to index in one go during bulk indexing
     */
    const BULK_SIZE_DEFAULT = 500;

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

        foreach ($data as $item) {
            $bulk['body'][] = [
                'index' => [
                    '_index' => $this->getIndex(),
                    '_type'  => $this->getType(),
                    '_id'    => $this->getItemId($item),
                ],
                $this->getItemBody($item),
            ];

            if (count($bulk['body']) === $this->getBulkSize()) {
                $service->bulk($bulk);
                $bulk = [];
            }

            $bar->advance();
        }

        // Flush remaining items
        if (!empty($bulk['body'])) {
            $service->bulk($bulk);
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
        return self::BULK_SIZE_DEFAULT;
    }


    /**
     * @return ElasticsearchServiceContract
     */
    private function getElasticsearchService()
    {
        return app(ElasticsearchServiceContract::class);
    }
}
