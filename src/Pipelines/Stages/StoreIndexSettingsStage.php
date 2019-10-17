<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;

/**
 * Class StoreIndexSettingsStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class StoreIndexSettingsStage implements StageInterface
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
        // Store the current number_of_replicas setting value in the payload
        $index = $this->elasticsearchService->getPrefixedIndexName($payload->getTargetVersionName());

        $settings = $this->elasticsearchService->indices()->getSettings(['index' => $index]);

        $indexSettings = $settings[$index]['settings']['index'];
        $payload->setNumberOfReplicas($indexSettings['number_of_replicas']);

        return $payload;
    }
}
