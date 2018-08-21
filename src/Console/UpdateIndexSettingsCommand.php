<?php

namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

/**
 * Class SetIndexSettingsCommand
 * @package namespace Nord\Lumen\Elasticsearch\Console
 */
class UpdateIndexSettingsCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'elastic:index:settings:update
                            { index : The name of the index to update }
                            {--numReplicas= : The value of index.refresh_interval that should be set } 
                            {--refreshInterval= : The value of index.refresh_interval that should be set }';

    /**
     * @var string
     */
    protected $description = 'Updates specified dynamic index settings for the specified index';

    /**
     * @var ElasticsearchServiceContract
     */
    private $elasticsearchService;

    /**
     * SetIndexSettingsCommand constructor.
     *
     * @param ElasticsearchServiceContract $elasticsearchService
     */
    public function __construct(ElasticsearchServiceContract $elasticsearchService)
    {
        parent::__construct();

        $this->elasticsearchService = $elasticsearchService;
    }

    public function handle(): void
    {
        $index           = $this->input->getArgument('index');
        $numReplicas     = $this->input->getOption('numReplicas');
        $refreshInterval = $this->input->getOption('refreshInterval');

        $settings = [];

        if ($numReplicas !== null) {
            $settings['number_of_replicas'] = (int)$numReplicas;
        }

        if ($refreshInterval !== null) {
            $settings['refresh_interval'] = $refreshInterval;
        }

        if (!empty($settings)) {
            $this->putIndexSettings($index, $settings);
        }
    }

    /**
     * @param string $index
     * @param array  $settings
     */
    private function putIndexSettings(string $index, array $settings): void
    {
        $this->elasticsearchService->indices()->putSettings([
            'index' => $index,
            'body'  => $settings,
        ]);
    }
}
