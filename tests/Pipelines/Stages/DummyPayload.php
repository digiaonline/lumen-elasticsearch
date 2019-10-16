<?php

namespace Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages;

use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;

/**
 * Class DummyPayload
 * @package Nord\Lumen\Elasticsearch\Tests\Pipelines\Stages
 */
class DummyPayload extends ApplyMigrationPayload
{

    /**
     * DummyPayload constructor.
     */
    public function __construct()
    {
        parent::__construct('/tmp', 100);
    }

    /**
     * @inheritDoc
     */
    public function getTargetConfiguration()
    {
        return ['index' => 'foo23'];
    }

    /**
     * @inheritDoc
     */
    public function getTargetVersionName()
    {
        return 'foo23';
    }

    /**
     * @inheritDoc
     */
    public function getIndexName()
    {
        return 'foo';
    }
}
