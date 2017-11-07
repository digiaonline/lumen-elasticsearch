<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Stages\StoreIndexSettingsStage;

/**
 * Class StoreIndexSettingsStageTest
 * @package Pipelines\Stages
 */
class StoreIndexSettingsStageTest extends AbstractStageTestCase
{

    /**
     *
     */
    public function testStage()
    {
        $indices       = $this->getMockedIndices(['getSettings', 'putSettings']);
        $searchService = $this->getMockedSearchService($indices);

        // Return some fake settings
        $indices->expects($this->once())
                ->method('getSettings')
                ->willReturn([
                    'foo23' => [
                        'settings' => [
                            'index' => [
                                'number_of_replicas' => 5,
                            ],
                        ],
                    ],
                ]);

        $stage   = new StoreIndexSettingsStage($searchService);
        $payload = $stage(new DummyPayload());

        // Check that the number of replicas was stored in the payload
        $this->assertEquals(5, $payload->getNumberOfReplicas());
    }
}
