<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CheckIndexExistsStage;

/**
 * Class CheckIndexExistsStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class CheckIndexExistsStageTest extends AbstractStageTestCase
{

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\IndexExistsException
     */
    public function testStage()
    {
        $indices = $this->getMockedIndices(['exists']);

        $indices->expects($this->once())
                ->method('exists')
                ->with(['index' => 'content_1'])
                ->willReturn(true);

        $service = $this->getMockedSearchService($indices);

        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $payload->setTargetVersionFile('1.php');

        $stage = new CheckIndexExistsStage($service);
        $this->assertInstanceOf(ApplyMigrationPayload::class, $stage($payload));
    }
}
