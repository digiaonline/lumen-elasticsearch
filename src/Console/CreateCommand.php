<?php namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

class CreateCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index:create {config : Configuration file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an Elasticsearch index from a configuration file.';


    /**
     * @inheritdoc
     */
    public function handle()
    {
        $config = $this->argument('config');

        $filePath = realpath($config);

        if (!file_exists($filePath)) {
            $this->error(sprintf("Configuration file '%s' does not exist.", $config));

            return 1;
        }

        $params = require($filePath);

        $this->info('Creating index ...');

        $service = $this->getElasticsearchService();
        $service->indices()->create($params);

        $this->info(sprintf("Index '%s' created.", $params['index']));

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
