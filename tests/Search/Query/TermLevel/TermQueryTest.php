<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class TermQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class TermQueryTest extends AbstractQueryTestCase
{

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
