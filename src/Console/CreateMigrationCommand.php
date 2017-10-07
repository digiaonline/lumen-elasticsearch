<?php

namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use League\Pipeline\Pipeline;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CreateVersionDefinitionStage;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\EnsureIndexConfigurationFileExistsStage;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\EnsureIndexVersionsDirectoryExists;

/**
 * Class CreateMigrationCommand
 * @package Nord\Lumen\Elasticsearch\Commands\Migrations
 */
class CreateMigrationCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'search:migrations:create {config : The path to the index configuration file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new migration using the specified index configuration';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $configurationPath = $this->argument('config');

        $pipeline = new Pipeline([
            new EnsureIndexConfigurationFileExistsStage(),
            new EnsureIndexVersionsDirectoryExists(),
            new CreateVersionDefinitionStage(),
        ]);

        $payload = new CreateMigrationPayload($configurationPath);
        $pipeline->process($payload);

        $this->output->writeln('Created ' . $payload->getVersionName());
    }

}
