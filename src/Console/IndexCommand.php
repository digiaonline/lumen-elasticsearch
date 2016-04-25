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
     * @return mixed
     */
    abstract public function getItemParent($item);


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
            $document = [
                'index' => $this->getIndex(),
                'type'  => $this->getType(),
                'id'    => $this->getItemId($item),
                'body'  => $this->getItemBody($item),
            ];

            if (($parent = $this->getItemParent($item)) !== null) {
                $document['parent'] = $parent;
            }

            $service->index($document);

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
