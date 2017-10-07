<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use League\Pipeline\StageInterface;

/**
 * Class EnsureIndexConfigurationFileExistsStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class EnsureIndexConfigurationFileExistsStage implements StageInterface
{

    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException if the configuration file specified in the payload doesn't exist
     */
    public function __invoke($payload)
    {
        /** @var CreateMigrationPayload $payload */
        $configurationPath = $payload->getConfigurationPath();
        
        if (!file_exists($configurationPath)) {
            throw new \InvalidArgumentException(sprintf('The specified configuration file %s does not exist',
                $configurationPath));
        }

        return $payload;
    }
}
