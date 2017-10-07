<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

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
        $query = $this->queryBuilder->createTermsQuery();
        $query->setField('field')
              ->setValues(['val1', 'val2']);

        $this->assertEquals([
            'terms' => ['field' => ['val1', 'val2']],
        ], $query->toArray());
    }

}
