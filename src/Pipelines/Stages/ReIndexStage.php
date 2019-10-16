<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
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
        /** @var ApplyMigrationPayload $payload */
        // Reindex data from the old index to the new, but only if the old index exists (not true on brand new setups)
        $oldIndex = $payload->getPrefixedIndexName();
        $newIndex = $payload->getPrefixedTargetVersionName();

        if ($this->elasticsearchService->indices()->exists(['index' => $oldIndex])) {
            // Temporarily change some index settings to speed up the process
            $this->elasticsearchService->indices()->putSettings([
                'index' => $newIndex,
                'body'  => [
                    'refresh_interval'   => -1,
                    'number_of_replicas' => 0,
                ],
            ]);

            $task = $this->elasticsearchService->reindex([
                'wait_for_completion' => false,
                'body'                => [
                    'source' => [
                        'index' => $oldIndex,
                        'size'  => $payload->getBatchSize(),
                    ],
                    'dest'   => [
                        'index' => $newIndex,
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
                    /** @var ProgressBar $progressBar */
                    $progressBar->setProgress($response['task']['status']['created']);
                }
            } catch (ServerErrorResponseException $e) {
            }

            sleep(1);
        } while ((bool)$response['completed'] === false);

        // For very short migrations we may never get a progress bar, because the task finishes too quickly
        if ($progressBar !== null) {
            $progressBar->finish();
        }

        echo PHP_EOL;
    }
}
