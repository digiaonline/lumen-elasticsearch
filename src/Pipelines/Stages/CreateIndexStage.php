<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;

/**
 * Class CreateIndexStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class CreateIndexStage implements StageInterface
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
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        /** @var ApplyMigrationPayload $payload */
        // Create the new index
        $this->elasticsearchService->indices()->create($payload->getTargetConfiguration());

        // Reindex data from the old index to the new, but only if the old index exists (not true on brand new setups)
        if ($this->elasticsearchService->indices()->exists(['index' => $payload->getIndexName()])) {
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
