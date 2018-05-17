<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\BoostMode;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\FunctionScoreQuery;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermQuery;
use Nord\Lumen\Elasticsearch\Search\Scoring\Functions\ScriptScoringFunction;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

class FunctionScoreQueryTest extends AbstractQueryTestCase
{
    /**
     *
     * @throws \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument
     */
    public function testToArray()
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new TermQuery('field1', 'value1'));

        $scriptScoringFunction = (new ScriptScoringFunction())
            ->setParams([
                'featured_content' => ['abc', 'xyz']
            ])->setInline("int score =0;for(item in params.featured_content){ if (doc['identifier'].indexOf(item) > -1) { score+=10;}}return score;");

        $functionScoreQuery = new FunctionScoreQuery();

        $functionScoreQuery
            ->setQuery($boolQuery)
            ->setWeight(34.12)
            ->setBoost(5.1)
            ->setMaxBoost(14.3)
            ->setBoostMode(BoostMode::MODE_MULTIPLY)
            ->setMinScore(1)
            ->addFunction($scriptScoringFunction);

        $expectedArray = [
            'function_score' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => ['field1' => 'value1'],
                            ],
                        ],
                    ]
                ],
                'functions' => [
                    [
                        'script_score' => [
                            'script' => [
                                'inline' => "int score =0;for(item in params.featured_content){ if (doc['identifier'].indexOf(item) > -1) { score+=10;}}return score;",
                                'params' => [
                                    'featured_content' => ['abc', 'xyz']
                                ]
                            ]
                        ]
                    ]
                ],
                'weight'     => 34.12,
                'boost'      => 5.1,
                'max_boost'  => 14.3,
                'boost_mode' => 'multiply',
                'min_score'  => 1,
            ]
        ];

        $this->assertEquals($expectedArray, $functionScoreQuery->toArray());
    }

    /**
     *
     */
    public function testToArrayMinimumFields()
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new TermQuery('field1', 'value1'));

        $functionScoreQuery = new FunctionScoreQuery();

        $functionScoreQuery
            ->setQuery($boolQuery);

        $expectedArray = [
            'function_score' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => ['field1' => 'value1'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $functionScoreQuery->toArray());
    }
}
