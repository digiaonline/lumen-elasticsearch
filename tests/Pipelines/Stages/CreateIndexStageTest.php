<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\CreateIndexStage;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class CreateIndexStageTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class CreateIndexStageTest extends TestCase
{

    /**
     *
     */
    public function testStage()
    {
        $indices = $this->getMockedIndices();

        $indices->expects($this->once())
                ->method('create')
                ->with(['index' => 'foo23']);

        $searchService = $this->getMockedSearchService($indices);

        $stage = new CreateIndexStage($searchService);
        $stage(new DummyPayload());
    }

    /**
     * @return IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedIndices()
    {
        return $this->getMockBuilder(IndicesNamespace::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['create'])
                    ->getMock();
    }

    /**
     * @param IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $mockedIndices
     *
     * @return ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedSearchService($mockedIndices)
    {
        $searchService = $this->getMockBuilder(ElasticsearchServiceContract::class)
                              ->setMethods(['indices'])
                              ->getMockForAbstractClass();

        $searchService->method('indices')
                      ->willReturn($mockedIndices);

        return $searchService;
    }

}
