<?php

/**
 * Class QueryTraitsTest
 */
class QueryTraitsTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Tests that the traits correctly set and get values
     */
    public function testTraits()
    {
        $this->specify('exception is thrown when invalid boost value is specified', function () {
            $query = new TraitTesterQuery();
            $query->setBoost('foo');
        }, ['throws' => new \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument()]);

        $this->specify('traits behave correctly', function () {
            $query = new TraitTesterQuery();
            $query->setBoost(2.0)
                  ->setField('field')
                  ->setValue('value')
                  ->setFields(['otherField'])
                  ->setValues(['otherValue']);

            verify($query->toArray())->equals([
                'boost'  => 2.0,
                'field'  => 'field',
                'value'  => 'value',
                'fields' => ['otherField'],
                'values' => ['otherValue'],
            ]);
        });
    }

    /**
     * Tests the HasScoreMode trait
     */
    public function testHasScoreModeTrait()
    {
        $this->specify('exception is thrown when invalid score mode is specified', function () {
            $query = new ScoreModeTesterQuery();
            $query->setScoreMode(\Nord\Lumen\Elasticsearch\Search\Query\ScoreMode::MODE_SUM);
        }, ['throws' => new \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument()]);
    }
}

/**
 * Class TraitTesterQuery
 */
class TraitTesterQuery extends \Nord\Lumen\Elasticsearch\Search\Query\QueryDSL
{
    use \Nord\Lumen\Elasticsearch\Search\Traits\HasField;
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasFields;
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValues;

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
class ScoreModeTesterQuery extends \Nord\Lumen\Elasticsearch\Search\Query\QueryDSL
{
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasScoreMode;

    /**
     * @inheritdoc
     */
    protected function getValidScoreModes()
    {
        return [
            \Nord\Lumen\Elasticsearch\Search\Query\ScoreMode::MODE_MAX,
        ];
    }

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
