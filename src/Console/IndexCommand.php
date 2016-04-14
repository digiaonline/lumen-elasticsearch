<?php namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

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

        foreach ($data as $item) {
            $service->index([
                'index' => $this->getIndex(),
                'type'  => $this->getType(),
                'id'    => $this->getItemId($item),
                'body'  => $this->getItemBody($item),
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->info("\nDone!");

        return 0;
    }


    /**
     * @return ElasticsearchServiceContract
     */
    private function getElasticsearchService()
    {
        return app(ElasticsearchServiceContract::class);
    }
}
