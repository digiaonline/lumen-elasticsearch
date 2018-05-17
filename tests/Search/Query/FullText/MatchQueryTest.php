<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Search\Query\FullText\MatchQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class MatchQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\FullText
 */
class MatchQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value');

        $this->assertEquals([
            'match' => [
                'field' => 'value',
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setOperator('and');

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query'    => 'value',
                    'operator' => 'and',
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setZeroTermsQuery('none');

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query'            => 'value',
                    'zero_terms_query' => 'none',
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setCutOffFrequency(0.001);

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query'            => 'value',
                    'cutoff_frequency' => 0.001,
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setType('phrase');

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query' => 'value',
                    'type'  => 'phrase',
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setType('phrase')
              ->setSlop(0);

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query' => 'value',
                    'type'  => 'phrase',
                    'slop'  => 0,
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setType('phrase_prefix')
              ->setMaxExpansions(10);

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query'          => 'value',
                    'type'           => 'phrase_prefix',
                    'max_expansions' => 10,
                ],
            ],
        ], $query->toArray());

        $query = new MatchQuery();
        $query->setField('field')
              ->setValue('value')
              ->setAnalyzer('custom_analyzer');

        $this->assertEquals([
            'match' => [
                'field' => [
                    'query'    => 'value',
                    'analyzer' => 'custom_analyzer',
                ],
            ],
        ], $query->toArray());
    }
}
