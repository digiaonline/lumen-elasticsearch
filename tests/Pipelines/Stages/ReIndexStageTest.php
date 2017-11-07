<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Stages\ReIndexStage;

/**
 * Class CreateIndexStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class ReIndexStageTest extends AbstractStageTestCase
{

    /**
     * Tests that we reindex from the old to the new index when the index exists
     */
    public function testWhenIndexExists()
    {
        $indices       = $this->getMockedIndices(['exists', 'putSettings']);
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
                              'source' => [
                                  'index' => 'foo',
                                  'size'  => 100,
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
        $indices       = $this->getMockedIndices(['exists', 'putSettings']);
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
}
