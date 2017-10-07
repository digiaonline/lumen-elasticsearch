<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use League\Pipeline\StageInterface;

/**
 * Class CreateVersionDefinitionStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class CreateVersionDefinitionStage implements StageInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        /** @var CreateMigrationPayload $payload */

        // Determine the path to the version definition
        $versionPath = $payload->getVersionPath();

        // Create the version
        $configuration          = $payload->getConfiguration();
        $configuration['index'] = $payload->getIndexVersionName();

        file_put_contents($versionPath, '<?php return ' . var_export($configuration, true) . ";\n");

        return $payload;
    }

}
