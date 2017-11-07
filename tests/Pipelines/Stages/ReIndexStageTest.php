<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\ReIndexStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class CreateIndexStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class ReIndexStageTest extends TestCase
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

        // Expect settings to be applied
        $indices->expects($this->once())
                ->method('putSettings');

        $searchService->expects($this->once())
                      ->method('reindex')
                      ->with([
                          'body' => [
                              'size'   => 100,
                              'source' => [
                                  'index' => 'foo',
                              ],
                              'dest'   => [
                                  'index' => 'foo23',
                              ],
                          ],
                      ]);

        $stage = new ReIndexStage($searchService);
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

        // Neither settings nor reindexing should occur
        $indices->expects($this->never())
                ->method('putSettings');

        $searchService->expects($this->never())
                      ->method('reindex');

        $stage = new ReIndexStage($searchService);
        $stage(new DummyPayload());
    }

    /**
     * @return IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedIndices()
    {
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['exists', 'putSettings'])
                        ->getMock();

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
