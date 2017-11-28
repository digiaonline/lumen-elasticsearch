<?php

namespace Nord\Lumen\Elasticsearch\Tests\Helpers;

use Nord\Lumen\Elasticsearch\Helpers\ArrayHelper;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class ArrayHelperTest
 * @package Nord\Lumen\Elasticsearch\Tests\Helpers
 */
class ArrayHelperTest extends TestCase
{
    public function testToTableRowsInput()
    {
        $raw = [
            'took'     => 1,
            'time_out' => false,
            'created'  => 120,
            'failures' => [],
            'retries'  => [
                'bulk'   => 0,
                'search' => 0,
            ]
        ];

        $rows = ArrayHelper::toTableRowsInput($raw);

        $expected = [
            ['took', 1],
            ['time_out', 'false'],
            ['created', 120],
            ['failures', '[]'],
            ['retries', 'bulk=0,search=0']
        ];

        $this->assertEquals($rows, $expected);
    }
}
