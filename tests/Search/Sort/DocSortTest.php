<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Sort;

use Nord\Lumen\Elasticsearch\Search\Sort\DocSort;

/**
 * Class DocSortTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Sort
 */
class DocSortTest extends AbstractSortTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $sort = $this->sortBuilder->createDocSort();
        $this->assertInstanceOf(DocSort::class, $sort);

        // Check empty
        $this->assertEquals('_doc', $sort->toArray());

        // Check with a sort
        $sort->setOrder('asc');
        $this->assertEquals(['_doc' => ['order' => 'asc']], $sort->toArray());
    }

}
