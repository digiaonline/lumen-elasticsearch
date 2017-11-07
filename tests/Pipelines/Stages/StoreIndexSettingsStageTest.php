<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\StoreIndexSettingsStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class StoreIndexSettingsStageTest
 * @package Pipelines\Stages
 */
class StoreIndexSettingsStageTest extends TestCase
{

    /**
     *
     */
    public function testStage()
    {
        $indices       = $this->getMockedIndices();
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

    /**
     * @return IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedIndices()
    {
        $indices = $this->getMockBuilder(IndicesNamespace::class)
                        ->disableOriginalConstructor()
                        ->setMethods(['getSettings', 'putSettings'])
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
