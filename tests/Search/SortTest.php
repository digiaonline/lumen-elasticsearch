<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search;

use Nord\Lumen\Elasticsearch\Search\Sort;
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
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->sort        = $this->service->createSort();
    }

    /**
     * Tests setters & getters.
     */
    public function testSetterGetter()
    {
        $this->sort->setSorts([
            (new Sort\FieldSort())->setField('f'),
            new Sort\ScoreSort(),
        ]);
        $this->assertCount(2, $this->sort->getSorts());

        $this->sort->addSort(new Sort\ScoreSort());
        $this->assertCount(3, $this->sort->getSorts());
    }
}
