<?php

/**
 * Class PartialQueryTest
 */
class PartialQueryTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\Search\Query\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        $service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));

        $this->queryBuilder = $service->createQueryBuilder();
    }

    /**
     *
     */
    public function testWildcardQuery()
    {
        $this->specify('exception is thrown when parameters are missing', function () {
            $this->queryBuilder->createWildcardQuery()->toArray();
        }, ['throws' => new \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument()]);

        $this->specify('query array format is correct', function () {
            $query = $this->queryBuilder->createWildcardQuery()->setField('foo')->setValue('bar?baz*qux');

            verify($query->toArray())->equals([
                'wildcard' => [
                    'foo' => 'bar?baz*qux',
                ],
            ]);
        });
    }

    /**
     *
     */
    public function testRegexpQuery()
    {
        $this->specify('exception is thrown when parameters are missing', function () {
            $this->queryBuilder->createRegexpQuery()->toArray();
        }, ['throws' => new \Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument()]);

        $this->specify('query array format is correct', function () {
            $query = $this->queryBuilder->createRegexpQuery()->setField('foo')->setValue('bar[0-9]');

            verify($query->toArray())->equals([
                'regexp' => [
                    'foo' => 'bar[0-9]',
                ],
            ]);
        });
    }
}
