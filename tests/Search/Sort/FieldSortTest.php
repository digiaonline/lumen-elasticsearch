<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Sort;

/**
 * Class FieldSortTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Sort
 */
class FieldSortTest extends AbstractSortTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field');
        $this->assertEquals('field', $sort->toArray());

        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field')->setOrder('asc');
        $this->assertEquals(['field' => ['order' => 'asc']], $sort->toArray());

        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field')->setMode('avg');
        $this->assertEquals(['field' => ['mode' => 'avg']], $sort->toArray());

        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field')->setOrder('asc')->setMode('avg');
        $this->assertEquals(['field' => ['order' => 'asc', 'mode' => 'avg']], $sort->toArray());

        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field')->setMissing('_last');
        $this->assertEquals(['field' => ['missing' => '_last']], $sort->toArray());

        $sort = $this->sortBuilder->createFieldSort()
                                  ->setField('field')->setUnmappedType('long');
        $this->assertEquals(['field' => ['unmapped_type' => 'long']], $sort->toArray());
    }

}
