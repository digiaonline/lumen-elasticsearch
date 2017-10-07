<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use League\Pipeline\StageInterface;

/**
 * Class DetermineTargetVersionStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class DetermineTargetVersionStage implements StageInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        /** @var ApplyMigrationPayload $payload */

        $highestVersion = 0;

        foreach (scandir($payload->getIndexVersionsPath(), SCANDIR_SORT_NONE) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $version = (int)substr($file, 0, strpos($file, '.php'));

            if ($version > $highestVersion) {
                $highestVersion = $version;
                
                $payload->setTargetVersionFile($file);
            }
        }

        return $payload;
    }
}
