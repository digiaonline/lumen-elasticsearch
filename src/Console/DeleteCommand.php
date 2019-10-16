<?php namespace Nord\Lumen\Elasticsearch\Console;

use Nord\Lumen\Elasticsearch\IndexNamePrefixer;

class DeleteCommand extends AbstractCommand
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
        $index = IndexNamePrefixer::getPrefixedIndexName((string)$this->argument('index'));

        $this->info(sprintf("Deleting index '%s' ...", $index));

        $this->elasticsearchService->indices()->delete(['index' => $index]);

        $this->info(sprintf("Index '%s' deleted.", $index));

        return 0;
    }
}
