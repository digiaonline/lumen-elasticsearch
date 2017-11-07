<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Elasticsearch\Namespaces\IndicesNamespace;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class AbstractStageTestCase
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;
 */
abstract class AbstractStageTestCase extends TestCase
{

    /**
     * @param array $methods
     *
     * @return IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockedIndices($methods = [])
    {
        return $this->getMockBuilder(IndicesNamespace::class)
                    ->disableOriginalConstructor()
                    ->setMethods($methods)
                    ->getMock();
    }

    /**
     * @param IndicesNamespace|\PHPUnit_Framework_MockObject_MockObject $mockedIndices
     *
     * @return ElasticsearchServiceContract|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockedSearchService($mockedIndices)
    {
        $searchService = $this->getMockBuilder(ElasticsearchServiceContract::class)
                              ->setMethods(['indices'])
                              ->getMockForAbstractClass();

        $searchService->method('indices')
                      ->willReturn($mockedIndices);

        return $searchService;
    }
}
