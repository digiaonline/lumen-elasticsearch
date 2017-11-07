<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

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

            $this->elasticsearchService->reindex([
                'body' => [
                    'source' => [
                        'index' => $payload->getIndexName(),
                        'size'  => $payload->getBatchSize(),
                    ],
                    'dest'   => [
                        'index' => $payload->getTargetVersionName(),
                    ],
                ],
            ]);
        }

        return $payload;
    }
}
