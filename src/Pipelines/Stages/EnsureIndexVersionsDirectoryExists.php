<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use League\Pipeline\StageInterface;

/**
 * Class EnsureIndexVersionsDirectoryExists
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class EnsureIndexVersionsDirectoryExists implements StageInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        /** @var CreateMigrationPayload $payload */
        if (!file_exists($payload->getIndexVersionsPath())) {
            mkdir($payload->getIndexVersionsPath(), 0777, true);
        }

        return $payload;
    }
}
