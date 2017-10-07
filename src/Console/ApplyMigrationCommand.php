<?php

namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use League\Pipeline\Pipeline;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Exceptions\IndexExistsException;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CheckIndexExistsStage;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CreateIndexStage;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\DetermineTargetVersionStage;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\UpdateIndexAliasStage;

/**
 * Class ApplyMigrationCommand
 * @package Nord\Lumen\Elasticsearch\Commands\Migrations
 */
class ApplyMigrationCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'search:migrations:migrate {config : The path to the index configuration file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates the specified index to a new index using the newest configuration version';

    /**
     * @var ElasticsearchServiceContract
     */
    protected $elasticsearchService;

    /**
     * AbstractMigrationCommand constructor.
     *
     * @param ElasticsearchServiceContract $elasticsearchService
     */
    public function __construct(ElasticsearchServiceContract $elasticsearchService)
    {
        parent::__construct();

        $this->elasticsearchService = $elasticsearchService;
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $configurationPath = $this->argument('config');

        $pipeline = new Pipeline([
            new DetermineTargetVersionStage(),
            new CheckIndexExistsStage($this->elasticsearchService),
            new CreateIndexStage($this->elasticsearchService),
            new UpdateIndexAliasStage($this->elasticsearchService),
        ]);

        $payload = new ApplyMigrationPayload($configurationPath);

        try {
            $pipeline->process($payload);

            $this->output->writeln(sprintf('Migrated %s to %s', $payload->getIndexName(),
                $payload->getTargetVersionName()));
        } catch (IndexExistsException $e) {
            $this->output->writeln('No migration required');
        }
    }

}
