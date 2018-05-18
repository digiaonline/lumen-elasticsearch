<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermsQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class TermsQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class TermsQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        // Test with constructor
        $query = new TermsQuery('field', ['val1', 'val2']);

        $this->assertEquals([
            'terms' => ['field' => ['val1', 'val2']],
        ], $query->toArray());

        // Test with setters
        $query = new TermsQuery();
        $query->setField('field')
              ->setValues(['val1', 'val2']);

        $this->assertEquals([
            'terms' => ['field' => ['val1', 'val2']],
        ], $query->toArray());
    }
}
