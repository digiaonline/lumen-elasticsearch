<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Search\Query\FullText\MultiMatchQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class MultiMatchQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\FullText
 */
class MultiMatchQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = $this->queryBuilder->createMultiMatchQuery();
        $query->setFields(['field1', 'field2'])
              ->setValue('value');

        $this->assertEquals([
            'multi_match' => [
                'query'  => 'value',
                'fields' => ['field1', 'field2'],
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createMultiMatchQuery();
        $query->setTieBreaker(0.3)
              ->setFields(['field1', 'field2'])
              ->setValue('value');

        $this->assertEquals([
            'multi_match' => [
                'query'       => 'value',
                'fields'      => ['field1', 'field2'],
                'tie_breaker' => 0.3,
            ],
        ], $query->toArray());

        $query = $this->queryBuilder->createMultiMatchQuery();
        $query->setFields(['field1', 'field2'])
              ->setValue('value')
              ->setType(MultiMatchQuery::TYPE_CROSS_FIELDS);

        $this->assertEquals([
            'multi_match' => [
                'query'  => 'value',
                'fields' => ['field1', 'field2'],
                'type'   => 'cross_fields',
            ],
        ], $query->toArray());
    }
}
