<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\FullText\MatchQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Joining\NestedQuery;
use Nord\Lumen\Elasticsearch\Search\Query\ScoreMode;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class NestedQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining
 */
class NestedQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new NestedQuery();
        $query->setPath('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new MatchQuery())
                              ->setField('field')
                              ->setValue('value')
                      )
              );

        $this->assertEquals([
            'nested' => [
                'path'  => 'doc',
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['field' => 'value']],
                        ],
                    ],
                ],
            ],
        ], $query->toArray());

        $query = new NestedQuery();
        $query->setPath('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new MatchQuery())
                              ->setField('field')
                              ->setValue('value')
                      )
              )
              ->setScoreMode(ScoreMode::MODE_AVG);

        $this->assertEquals([
            'nested' => [
                'path'       => 'doc',
                'query'      => [
                    'bool' => [
                        'must' => [
                            ['match' => ['field' => 'value']],
                        ],
                    ],
                ],
                'score_mode' => 'avg',
            ],
        ], $query->toArray());
    }

    /**
     * @expectedException \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument
     */
    public function testToArrayWithMissingQuery()
    {
        (new NestedQuery())->toArray();
    }
}
