<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Scoring\Functions\ScriptScoringFunction;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

class FunctionScoreQueryTest extends AbstractQueryTestCase
{
    /**
     *
     */
    public function testToArray()
    {
        $boolQuery = $this->queryBuilder->createBoolQuery();
        $boolQuery->addMust($this->queryBuilder->createTermQuery()->setField('field1')->setValue('value1'));

        $scriptScoringFunction = (new ScriptScoringFunction())
            ->setParams([
                'featured_content' => ['abc', 'xyz']
            ])->setInline("int score =0;for(item in params.featured_content){ if (doc['identifier'].indexOf(item) > -1) { score+=10;}}return score;");

        $functionScoreQuery = $this->queryBuilder->createFunctionScoreQuery();

        $functionScoreQuery
            ->setQuery($boolQuery)
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
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $functionScoreQuery->toArray());
    }
}
