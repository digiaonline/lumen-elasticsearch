<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class ReIndexStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class ReIndexStage implements StageInterface
{

    /**
     * @var ElasticsearchServiceContract
     */
    private $elasticsearchService;

    /**
     * CheckIndexExistsStage constructor.
     *
     * @param ElasticsearchServiceContract $elasticsearchService
     */
    public function __construct(ElasticsearchServiceContract $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($payload)
    {
        // Reindex data from the old index to the new, but only if the old index exists (not true on brand new setups)
        if ($this->elasticsearchService->indices()->exists(['index' => $payload->getIndexName()])) {
            // Temporarily change some index settings to speed up the process
            $this->elasticsearchService->indices()->putSettings([
                'index' => $payload->getTargetVersionName(),
                'body'  => [
                    'refresh_interval'   => -1,
                    'number_of_replicas' => 0,
                ],
            ]);

            $task = $this->elasticsearchService->reindex([
                'wait_for_completion' => false,
                'body'                => [
                    'source' => [
                        'index' => $payload->getIndexName(),
                        'size'  => $payload->getBatchSize(),
                    ],
                    'dest'   => [
                        'index' => $payload->getTargetVersionName(),
                    ],
                ],
            ]);

            // Use a progress bar to indicate how far a long the re-indexing has come
            $this->renderProgressBar($task);
        }

        return $payload;
    }

    /**
     * Renders a progress bar until the specified re-indexing task is completed
     *
     * @param array $task
     */
    protected function renderProgressBar(array $task): void
    {
        // Use a progress bar to indicate how far a long the re-indexing has come
        $progressBar = null;

        do {
            $response = ['completed' => false];

            // Ignore ServerErrorResponseException, re-indexing can make these requests time out
            try {
                $response = $this->elasticsearchService->tasks()->get([
                    'task_id' => $task['task'],
                ]);

                $total = $response['task']['status']['total'];

                // Initialize the progress bar once Elasticsearch knows the total amount of items
                if ($progressBar === null && $total > 0) {
                    $progressBar = new ProgressBar(new ConsoleOutput(), $total);
                } elseif ($progressBar !== null) {
                    $progressBar->setProgress($response['task']['status']['created']);
                }
            } catch (ServerErrorResponseException $e) {
            }

            sleep(1);
        } while ((bool)$response['completed'] === false);

        $progressBar->finish();

        echo PHP_EOL;
    }
}
