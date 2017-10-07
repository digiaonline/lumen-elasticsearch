<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Payloads;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class CreateMigrationPayloadTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Payloads
 */
class CreateMigrationPayloadTest extends TestCase
{

    /**
     *
     */
    public function testGetters()
    {
        $payload = new StaticVersionCreateMigrationPayload($this->getResourcesBasePath() . '/content.php');

        $this->assertStringEndsWith('tests/Resources/versions/content/12345.php', $payload->getVersionPath());
        $this->assertEquals(12345, $payload->getVersionName());
        $this->assertEquals('content_12345', $payload->getIndexVersionName());
    }
}

/**
 * Class StaticVersionCreateMigrationPayload
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Payloads
 */
class StaticVersionCreateMigrationPayload extends CreateMigrationPayload
{

    /**
     * @inheritdoc
     */
    public function __construct($configurationPath)
    {
        parent::__construct($configurationPath);

        $this->versionName = '12345';
    }
}
