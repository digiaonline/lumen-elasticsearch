<?php

namespace Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\EnsureIndexConfigurationFileExistsStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class EnsureConfigurationExistsStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class EnsureIndexConfigurationFileExistsStageTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStage()
    {
        $payload = new CreateMigrationPayload('/does/not/exist');
        $stage   = new EnsureIndexConfigurationFileExistsStage();

        $stage($payload);
    }

}
