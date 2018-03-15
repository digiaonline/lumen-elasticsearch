<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Traits;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;
use Nord\Lumen\Elasticsearch\Search\Query\ScoreMode;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasFields;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasScoreMode;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValues;
use Nord\Lumen\Elasticsearch\Search\Traits\HasField;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class QueryTraitsTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Traits
 */
class QueryTraitsTest extends TestCase
{

    /**
     * Tests that the traits correctly set and get values
     */
    public function testTraits()
    {
        $query = new TraitTesterQuery();
        $query->setBoost(2.0)
              ->setField('field')
              ->setValue('value')
              ->setFields(['otherField'])
              ->setValues(['otherValue']);

        $this->assertEquals([
            'boost'  => 2.0,
            'field'  => 'field',
            'value'  => 'value',
            'fields' => ['otherField'],
            'values' => ['otherValue'],
        ], $query->toArray());
    }
}

/**
 * Class TraitTesterQuery
 */
class TraitTesterQuery extends QueryDSL
{
    use HasField;
    use HasBoost;
    use HasFields;
    use HasValue;
    use HasValues;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'boost'  => $this->getBoost(),
            'field'  => $this->getField(),
            'value'  => $this->getValue(),
            'fields' => $this->getFields(),
            'values' => $this->getValues(),
        ];
    }
}

/**
 * Class ScoreModeTesterQuery
 */
class ScoreModeTesterQuery extends QueryDSL
{
    use HasScoreMode;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'score_mode' => $this->getScoreMode(),
        ];
    }
}
