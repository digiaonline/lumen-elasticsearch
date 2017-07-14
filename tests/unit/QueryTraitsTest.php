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
     * @var TraitTesterQuery
     */
    protected $testQuery;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->testQuery = new TraitTesterQuery();
    }

    /**
     * Tests that the traits correctly set and get values
     */
    public function testTraits()
    {
        $this->specify('exception is thrown when invalid boost value is specified', function () {
            $this->testQuery->setBoost('foo');
        }, ['throws' => new \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument()]);

        $this->specify('traits behave correctly', function () {
            $this->testQuery->setBoost(2.0)
                            ->setField('field')
                            ->setValue('value')
                            ->setFields(['otherField'])
                            ->setValues(['otherValue']);

            verify($this->testQuery->toArray())->equals([
                'boost'  => 2.0,
                'field'  => 'field',
                'value'  => 'value',
                'fields' => ['otherField'],
                'values' => ['otherValue'],
            ]);
        });
    }
}

/**
 * Class TraitTesterQuery
 */
class TraitTesterQuery extends \Nord\Lumen\Elasticsearch\Search\Query\QueryDSL
{

    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
    use \Nord\Lumen\Elasticsearch\Search\Query\Traits\HasField;
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
