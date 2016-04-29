<?php

class SortStringParserTest extends \Codeception\TestCase\Test
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\ElasticsearchService
     */
    protected $service;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        /** @var \Elasticsearch\Client $client */
        $client = $this->getMockBuilder('\Elasticsearch\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService($client);
    }


    /**
     * Tests setting custom parser options.
     */
    public function testParserOptions()
    {
        $this->specify('set parser separator and delimiter', function () {
            $parser = $this->service->createSortStringParser(['separator' => '..', 'delimiter' => '/']);
            $sorts  = $parser->parse('field1/asc..field2/desc');
            verify($sorts)->equals([
                [0 => 'field1', 1 => 'asc'],
                [0 => 'field2', 1 => 'desc'],
            ]);
        });
    }


    /**
     * Tests parsing single sort item
     */
    public function testParseSingleSort()
    {
        $this->specify('parses single sort item', function () {
            $sorts = $this->service->createSortStringParser()->parse('field');
            verify($sorts)->equals([
                [0 => 'field']
            ]);
        });


        $this->specify('parses single sort item with order', function () {
            $sorts = $this->service->createSortStringParser()->parse('field:asc');
            verify($sorts)->equals([
                [0 => 'field', 1 => 'asc']
            ]);
        });


        $this->specify('parses single sort item with order and mode', function () {
            $sorts = $this->service->createSortStringParser()->parse('field:asc:avg');
            verify($sorts)->equals([
                [0 => 'field', 1 => 'asc', 2 => 'avg']
            ]);
        });
    }


    /**
     * Tests parsing multi sort items
     */
    public function testParseMultiSort()
    {
        $this->specify('parses multi sort item', function () {
            $sorts = $this->service->createSortStringParser()->parse('field1;field2');
            verify($sorts)->equals([
                [0 => 'field1'],
                [0 => 'field2'],
            ]);
        });


        $this->specify('parses multi sort item with order', function () {
            $sorts = $this->service->createSortStringParser()->parse('field1:asc;field2:desc');
            verify($sorts)->equals([
                [0 => 'field1', 1 => 'asc'],
                [0 => 'field2', 1 => 'desc'],
            ]);
        });


        $this->specify('parses multi sort item with order on second sort', function () {
            $sorts = $this->service->createSortStringParser()->parse('field1;field2:asc');
            verify($sorts)->equals([
                [0 => 'field1'],
                [0 => 'field2', 1 => 'asc'],
            ]);
        });


        $this->specify('parses multi sort item with order and mode', function () {
            $sorts = $this->service->createSortStringParser()->parse('field1:asc:min;field2:desc:sum');
            verify($sorts)->equals([
                [0 => 'field1', 1 => 'asc', 2 => 'min'],
                [0 => 'field2', 1 => 'desc', 2 => 'sum'],
            ]);
        });


        $this->specify('parses multi sort item with order and mode on first sort', function () {
            $sorts = $this->service->createSortStringParser()->parse('field1:asc:min;field2:desc');
            verify($sorts)->equals([
                [0 => 'field1', 1 => 'asc', 2 => 'min'],
                [0 => 'field2', 1 => 'desc'],
            ]);
        });
    }


    /**
     * Tests building sort from string.
     */
    public function testBuildSortFromString()
    {
        $this->specify('builds sorts from simple sort string', function () {
            $sorts = $this->service->createSortStringParser()->buildSortFromString('_score;_doc');
            verify($sorts)->notEmpty();
            verify($sorts[0]->toArray())->equals('_score');
            verify($sorts[1]->toArray())->equals('_doc');
        });


        $this->specify('builds sorts from complex sort string', function () {
            $sorts = $this->service->createSortStringParser()->buildSortFromString('field1:asc;field2:desc:sum;_score');
            verify($sorts)->notEmpty();
            verify($sorts[0]->toArray())->equals(['field1' => ['order' => 'asc']]);
            verify($sorts[1]->toArray())->equals(['field2' => ['order' => 'desc', 'mode' => 'sum']]);
            verify($sorts[2]->toArray())->equals('_score');
        });
    }
}
