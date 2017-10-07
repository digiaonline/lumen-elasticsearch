<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search;

use Nord\Lumen\Elasticsearch\Search\Sort;
use Nord\Lumen\Elasticsearch\Search\Sort\SortBuilder;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class SortTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search
 */
class SortTest extends TestCase
{

    /**
     * @var Sort
     */
    protected $sort;

    /**
     * @var SortBuilder
     */
    protected $sortBuilder;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->sortBuilder = $this->service->createSortBuilder();
        $this->sort        = $this->service->createSort();
    }

    /**
     * Tests setters & getters.
     */
    public function testSetterGetter()
    {
        $this->sort->setSorts([
            $this->sortBuilder->createFieldSort()->setField('f'),
            $this->sortBuilder->createScoreSort(),
        ]);
        $this->assertCount(2, $this->sort->getSorts());

        $this->sort->addSort($this->sortBuilder->createScoreSort());
        $this->assertCount(3, $this->sort->getSorts());
    }

}
