<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Payloads;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class ApplyMigrationPayloadTest
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Payloads
 */
class ApplyMigrationPayloadTest extends TestCase
{

    /**
     *
     */
    public function testGettersSetters()
    {
        $payload = new ApplyMigrationPayload($this->getResourcesBasePath() . '/content.php', 100);

        $this->assertStringEndsWith('versions/content/', $payload->getTargetVersionPath());

        // Set the versions to 7.php and check that it is loaded
        $payload->setTargetVersionFile('7.php');
        $this->assertTrue(is_array($payload->getTargetConfiguration()));
        $this->assertEquals('content_7', $payload->getTargetVersionName());
        $this->assertEquals(100, $payload->getBatchSize());

        // Test number of replicas
        $payload->setNumberOfReplicas(5);
        $this->assertEquals(5, $payload->getNumberOfReplicas());
    }
}
