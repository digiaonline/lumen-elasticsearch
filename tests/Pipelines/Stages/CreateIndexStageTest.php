<?php

namespace Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CreateIndexStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class CreateIndexStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class CreateIndexStageTest extends TestCase
{

    /**
     * Tests that we reindex from the old to the new index when the index exists
     */
    public function testWhenIndexExists()
    {
        $indices       = $this->getMockedIndices();
        $searchService = $this->getMockedSearchService($indices);

        $indices->expects($this->once())
                ->method('exists')
                ->with(['index' => 'foo'])
                ->willReturn(true);

        $searchService->expects($this->once())
                      ->method('reindex')
                      ->with([
                          'body' => [
                              'source' => [
                                  'index' => 'foo',
                              ],
                              'dest'   => [
                                  'index' => 'foo23',
                              ],
                          ],
                      ]);

        $stage = new CreateIndexStage($searchService);
        $stage(new DummyPayload());
    }

    /**
     * Tests that we reindex from the old to the new index when the index exists
     */
    public function testWhenIndexDoesNotExist()
    {
        $indices       = $this->getMockedIndices();
        $searchService = $this->getMockedSearchService($indices);

        $indices->expects($this->once())
                ->method('exists')
                ->with(['index' => 'foo'])
                ->willReturn(false);

        $searchService->expects($this->never())
                      ->method('reindex');

        $stage = new CreateIndexStage($searchService);
        $stage(new DummyPayload());
    }

    /**
     * @return IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedIndices()
    {
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['create', 'exists'])
                        ->getMock();

        $indices->expects($this->once())
                ->method('create')
                ->with(['index' => 'foo23']);

        return $indices;
    }

    /**
     * @param IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $mockedIndices
     *
     * @return ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedSearchService($mockedIndices)
    {
        $searchService = $this->getMockBuilder(ElasticsearchServiceContract::class)
                              ->setMethods(['indices', 'reindex'])
                              ->getMockForAbstractClass();

        $searchService->method('indices')
                      ->willReturn($mockedIndices);

        return $searchService;
    }
}

/**
 * Class DummyPayload
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class DummyPayload extends ApplyMigrationPayload
{

    /**
     * DummyPayload constructor.
     */
    public function __construct()
    {
        parent::__construct('/tmp');
    }

    /**
     * @inheritDoc
     */
    public function getTargetConfiguration()
    {
        return ['index' => 'foo23'];
    }

    /**
     * @inheritDoc
     */
    public function getTargetVersionName()
    {
        return 'foo23';
    }

    /**
     * @inheritDoc
     */
    public function getIndexName()
    {
        return 'foo';
    }
}
