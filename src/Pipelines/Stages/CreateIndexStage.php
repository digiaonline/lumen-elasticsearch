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
        $params = $payload->getPrefixedTargetConfiguration();

        $this->elasticsearchService->indices()->create($params);

        return $payload;
    }
}
