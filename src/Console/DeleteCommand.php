<?php namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

class DeleteCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index:delete {index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes an Elasticsearch index.';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $index = $this->argument('index');

        $this->info('Deleting index ...');

        $service = $this->getElasticsearchService();
        $service->indices()->delete(['index' => $index]);

        $this->info(sprintf("Index '%s' deleted.", $index));

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
