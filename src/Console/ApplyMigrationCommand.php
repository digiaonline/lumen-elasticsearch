<?php

namespace Nord\Lumen\Elasticsearch\Console;

use League\Pipeline\Pipeline;
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
class ApplyMigrationCommand extends AbstractCommand
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
     * @inheritDoc
     */
    public function handle()
    {
        $configurationPath = (string)$this->argument('config');

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
