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
        $sort = new DocSort();

        // Check empty
        $this->assertEquals('_doc', $sort->toArray());

        // Check with a sort
        $sort->setOrder('asc');
        $this->assertEquals(['_doc' => ['order' => 'asc']], $sort->toArray());
    }
}
