<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class TermQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class TermQueryTest extends AbstractQueryTestCase
{

    public function testConstructor()
    {
        $query = new TermQuery('field', 'value');

        $this->assertEquals('field', $query->getField());
        $this->assertEquals('value', $query->getValue());
    }

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = $this->queryBuilder->createTermQuery();
        $query->setField('field')
              ->setValue('value');

        $this->assertEquals(['term' => ['field' => 'value']], $query->toArray());

        $query->setBoost(2.0);

        $this->assertEquals([
            'term' => [
                'field' => [
                    'value' => 'value',
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());
    }
}
