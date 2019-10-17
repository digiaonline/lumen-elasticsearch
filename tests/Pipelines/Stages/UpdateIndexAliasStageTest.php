<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\UpdateIndexAliasStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class UpdateIndexAliasStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class UpdateIndexAliasStageTest extends TestCase
{

    /**
     * Tests that the alias gets updated and orphaned indices get deleted
     */
    public function testNormalOperation()
    {
        /** @var IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $indices */
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getAlias', 'updateAliases', 'delete', 'putSettings'])
                        ->getMock();

        // Pretend there's an alias pointing at the index "content_3"
        $indices->expects($this->once())
                ->method('getAlias')
                ->with(['name' => 'content'])
                ->willReturn(['content_3' => 'foo']);

        // Expect settings to me updated
        $indices->expects($this->once())
                ->method('putSettings');

        // Expect the alias to get updated
        $indices->expects($this->once())
                ->method('updateAliases');

        // Expect "content_3" to be deleted
        $indices->expects($this->once())
                ->method('delete')
                ->with(['index' => 'content_3']);

        /** @var ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                        ->setMethods(['indices'])
                        ->getMockForAbstractClass();

        $service->expects($this->once())
                ->method('indices')
                ->willReturn($indices);

        $stage   = new UpdateIndexAliasStage($service);
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $payload->setTargetVersionFile('7.php');

        $this->assertInstanceOf(ApplyMigrationPayload::class, $stage($payload));
    }

    /**
     * Tests that the alias gets updated and orphaned indices get deleted
     */
    public function testNormalOperationWithPrefix()
    {
        putenv('ELASTICSEARCH_INDEX_PREFIX=test');
        
        /** @var IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $indices */
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getAlias', 'updateAliases', 'delete', 'putSettings'])
                        ->getMock();

        // Pretend there's an alias pointing at the index "content_3"
        $indices->expects($this->once())
                ->method('getAlias')
                ->with(['name' => 'test_content'])
                ->willReturn(['test_content_3' => 'foo']);

        // Expect settings to me updated
        $indices->expects($this->once())
                ->method('putSettings');

        // Expect the alias to get updated
        $indices->expects($this->once())
                ->method('updateAliases');

        // Expect "content_3" to be deleted
        $indices->expects($this->once())
                ->method('delete')
                ->with(['index' => 'test_content_3']);

        /** @var ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                        ->setMethods(['indices'])
                        ->getMockForAbstractClass();

        $service->expects($this->once())
                ->method('indices')
                ->willReturn($indices);

        $stage   = new UpdateIndexAliasStage($service);
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $payload->setTargetVersionFile('7.php');

        $this->assertInstanceOf(ApplyMigrationPayload::class, $stage($payload));
    }

    /**
     * Tests that a missing alias is handled correctly when no index exists
     */
    public function testMissingAliasMissingIndex()
    {
        /** @var IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $indices */
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getAlias', 'updateAliases', 'exists', 'delete', 'putSettings'])
                        ->getMock();

        // Make it so there are no aliases
        $indices->expects($this->once())
                ->method('getAlias')
                ->willThrowException(new Missing404Exception());

        // Make it so there is no index
        $indices->expects($this->once())
                ->method('exists')
                ->willReturn(false);

        // This means delete() shouldn't get called
        $indices->expects($this->never())
                ->method('delete');

        /** @var ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                        ->setMethods(['indices'])
                        ->getMockForAbstractClass();

        $service->expects($this->once())
                ->method('indices')
                ->willReturn($indices);

        $stage   = new UpdateIndexAliasStage($service);
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $payload->setTargetVersionFile('7.php');

        $stage($payload);
    }

    /**
     * Tests that a missing alias is handled correctly when no index exists
     */
    public function testMissingAlias()
    {
        /** @var IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $indices */
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getAlias', 'updateAliases', 'exists', 'delete', 'putSettings'])
                        ->getMock();

        // Make it so there are no aliases
        $indices->expects($this->once())
                ->method('getAlias')
                ->willThrowException(new Missing404Exception());

        // Make it so there is an index
        $indices->expects($this->once())
                ->method('exists')
                ->willReturn(true);

        // This means delete() shouldn't get called
        $indices->expects($this->once())
                ->method('delete')
                ->with(['index' => 'content']);

        /** @var ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMockBuilder(ElasticsearchServiceContract::class)
                        ->setMethods(['indices'])
                        ->getMockForAbstractClass();

        $service->expects($this->once())
                ->method('indices')
                ->willReturn($indices);

        $stage   = new UpdateIndexAliasStage($service);
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);
        $payload->setTargetVersionFile('7.php');

        $stage($payload);
    }
}
