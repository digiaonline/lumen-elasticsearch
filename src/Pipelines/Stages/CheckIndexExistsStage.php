<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Exceptions\IndexExistsException;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;

/**
 * Class CheckIndexExistsStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class CheckIndexExistsStage implements StageInterface
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
     *
     * @throws IndexExistsException
     */
    public function __invoke($payload)
    {
        /** @var ApplyMigrationPayload $payload */
        $index    = $payload->getPrefixedTargetVersionName();
        $response = $this->elasticsearchService->indices()->exists(['index' => $index]);

        if ($response) {
            throw new IndexExistsException($index);
        }

        return $payload;
    }
}
