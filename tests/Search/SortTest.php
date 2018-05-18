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
    public function testConstructor()
    {
        $sort = new Sort([new Sort\ScoreSort()]);

        $this->assertInstanceOf(Sort\ScoreSort::class, $sort->getSorts()[0]);
    }

    /**
     * Tests setters & getters.
     */
    public function testSetterGetter()
    {
        $sort = new Sort();
        $sort->setSorts([
            (new Sort\FieldSort())->setField('f'),
            new Sort\ScoreSort(),
        ]);

        $this->assertCount(2, $sort->getSorts());

        $sort->addSort(new Sort\ScoreSort());

        $this->assertCount(3, $sort->getSorts());
    }
}
