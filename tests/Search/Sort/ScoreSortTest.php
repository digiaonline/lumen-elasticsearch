<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Sort;

use Nord\Lumen\Elasticsearch\Search\Sort\ScoreSort;

/**
 * Class ScoreSortTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Sort
 */
class ScoreSortTest extends AbstractSortTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $sort = new ScoreSort();
        $this->assertEquals('_score', $sort->toArray());

        $sort->setOrder('asc');
        $this->assertEquals(['_score' => ['order' => 'asc']], $sort->toArray());
    }
}
