<?php

namespace Nord\Lumen\Elasticsearch\Tests\Parsers;

use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class SortStringParserTest
 * @package Nord\Lumen\Elasticsearch\Tests\Parsers
 */
class SortStringParserTest extends TestCase
{

    /**
     * Tests setting custom parser options.
     */
    public function testParserOptions()
    {
        $parser = $this->service->createSortStringParser(['separator' => '..', 'delimiter' => '/']);

        $this->assertEquals([
            [0 => 'field1', 1 => 'asc'],
            [0 => 'field2', 1 => 'desc'],
        ], $parser->parse('field1/asc..field2/desc'));
    }

    /**
     * Tests parsing single sort item
     */
    public function testParseSingleSort()
    {
        $sorts = $this->service->createSortStringParser()->parse('field');
        $this->assertEquals([
            [0 => 'field'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field:asc');
        $this->assertEquals([
            [0 => 'field', 1 => 'asc'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field:asc:avg');
        $this->assertEquals([
            [0 => 'field', 1 => 'asc', 2 => 'avg'],
        ], $sorts);
    }

    /**
     * Tests parsing multi sort items
     */
    public function testParseMultiSort()
    {
        $sorts = $this->service->createSortStringParser()->parse('field1;field2');
        $this->assertEquals([
            [0 => 'field1'],
            [0 => 'field2'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field1:asc;field2:desc');
        $this->assertEquals([
            [0 => 'field1', 1 => 'asc'],
            [0 => 'field2', 1 => 'desc'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field1;field2:asc');
        $this->assertEquals([
            [0 => 'field1'],
            [0 => 'field2', 1 => 'asc'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field1:asc:min;field2:desc:sum');
        $this->assertEquals([
            [0 => 'field1', 1 => 'asc', 2 => 'min'],
            [0 => 'field2', 1 => 'desc', 2 => 'sum'],
        ], $sorts);

        $sorts = $this->service->createSortStringParser()->parse('field1:asc:min;field2:desc');
        $this->assertEquals([
            [0 => 'field1', 1 => 'asc', 2 => 'min'],
            [0 => 'field2', 1 => 'desc'],
        ], $sorts);
    }

    /**
     * Tests building sort from string.
     */
    public function testBuildSortFromString()
    {
        $sorts = $this->service->createSortStringParser()->buildSortFromString('_score;_doc');
        $this->assertNotEmpty($sorts);
        $this->assertEquals('_score', $sorts[0]->toArray());
        $this->assertEquals('_doc', $sorts[1]->toArray());

        $sorts = $this->service->createSortStringParser()->buildSortFromString('field1:asc;field2:desc:sum;_score');
        $this->assertNotEmpty($sorts);
        $this->assertEquals(['field1' => ['order' => 'asc']], $sorts[0]->toArray());
        $this->assertEquals(['field2' => ['order' => 'desc', 'mode' => 'sum']], $sorts[1]->toArray());
        $this->assertEquals('_score', $sorts[2]->toArray());
    }
}
