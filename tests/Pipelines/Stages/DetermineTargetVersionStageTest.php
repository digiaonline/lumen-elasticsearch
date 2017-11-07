<?php

namespace Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\DetermineTargetVersionStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class DetermineTargetVersionStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class DetermineTargetVersionStageTest extends TestCase
{

    /**
     *
     */
    public function testStage()
    {
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $stage   = new DetermineTargetVersionStage();

        $payload = $stage($payload);

        $this->assertEquals('7.php', basename($payload->getTargetVersionPath()));
    }
}
