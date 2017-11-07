<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Stages\CreateIndexStage;

/**
 * Class CreateIndexStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class CreateIndexStageTest extends AbstractStageTestCase
{

    /**
     *
     */
    public function testStage()
    {
        $indices = $this->getMockedIndices(['create']);

        $indices->expects($this->once())
                ->method('create')
                ->with(['index' => 'foo23']);

        $searchService = $this->getMockedSearchService($indices);

        $stage = new CreateIndexStage($searchService);
        $stage(new DummyPayload());
    }
}
