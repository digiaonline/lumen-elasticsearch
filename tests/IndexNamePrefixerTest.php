<?php

namespace Nord\Lumen\Elasticsearch\Tests;

use Nord\Lumen\Elasticsearch\IndexNamePrefixer;

class IndexNamePrefixerTest extends TestCase
{

    public function testNoPrefixDefined(): void
    {
        putenv('ELASTICSEARCH_INDEX_PREFIX=');

        $this->assertEquals('foo', IndexNamePrefixer::getPrefixedIndexName('foo'));
        $this->assertEquals(['index' => 'foo'], IndexNamePrefixer::getPrefixedIndexParameters(['index' => 'foo']));
    }

    public function testPrefixDefined(): void
    {
        putenv('ELASTICSEARCH_INDEX_PREFIX=dev');

        $this->assertEquals('dev_foo', IndexNamePrefixer::getPrefixedIndexName('foo'));
        $this->assertEquals(['index' => 'dev_foo'], IndexNamePrefixer::getPrefixedIndexParameters(['index' => 'foo']));
    }
}
