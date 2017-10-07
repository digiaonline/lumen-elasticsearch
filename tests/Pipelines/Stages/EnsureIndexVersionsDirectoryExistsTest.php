<?php

namespace Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\CreateMigrationPayload;
use Nord\Lumen\Elasticsearch\Pipelines\Stages\EnsureIndexVersionsDirectoryExists;
use Nord\Lumen\Elasticsearch\Tests\TestCase;

/**
 * Class EnsureIndexVersionsDirectoryExistsTest
 * @package Nord\Lumen\Elasticsearch\Tests\Unit\Search\Pipelines\Stages
 */
class EnsureIndexVersionsDirectoryExistsTest extends TestCase
{

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        // Delete the directory the test creates if it exists
        $this->ensureDirectoryNotExists();
    }

    /**
     * @inheritDoc
     */
    public function tearDown()
    {
        // Delete the directory the test created
        $this->ensureDirectoryNotExists();
    }

    /**
     *
     */
    public function testStage()
    {
        $payload = new CreateMigrationPayload($this->getResourcesBasePath() . '/context.php');
        $stage   = new EnsureIndexVersionsDirectoryExists();

        $stage($payload);

        $this->assertDirectoryExists($this->getExpectedVersionsDirectory());
    }

    /**
     *
     */
    private function ensureDirectoryNotExists()
    {
        $directory = $this->getExpectedVersionsDirectory();

        if (file_exists($directory)) {
            rmdir($directory);
        }
    }

    /**
     * @return string
     */
    private function getExpectedVersionsDirectory()
    {
        return $this->getResourcesBasePath() . '/versions/context';
    }
}
