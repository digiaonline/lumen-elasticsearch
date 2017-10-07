<?php

namespace Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CheckIndexExistsStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class CheckIndexExistsStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class CheckIndexExistsStageTest extends TestCase
{

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\IndexExistsException
     */
    public function testStage()
    {
        /** @var IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $indices */
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['exists'])
                        ->getMock();

        $indices->expects($this->once())
                ->method('exists')
                ->with(['index' => 'content_1'])
                ->willReturn(true);

        /** @var ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                        ->setMethods(['indices'])
                        ->getMockForAbstractClass();

        $service->expects($this->once())
                ->method('indices')
                ->willReturn($indices);

        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php');
        $payload->setTargetVersionFile('1.php');

        $stage = new CheckIndexExistsStage($service);
        $stage($payload);
    }
}
