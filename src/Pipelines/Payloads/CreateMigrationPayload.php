<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Payloads;

/**
 * Class CreateMigrationPayload
 * @package Nord\Lumen\Elasticsearch\Pipelines\Payloads
 */
class CreateMigrationPayload extends MigrationPayload
{

    /**
     * @var int
     */
    protected $versionName;

    /**
     * IndexMigrationPayload constructor.
     *
     * @param string $configurationPath
     */
    public function __construct($configurationPath)
    {
        parent::__construct($configurationPath);

        // Determine the index name
        $this->versionName = time();
    }

    /**
     * @return string
     */
    public function getVersionPath()
    {
        return sprintf('%s/%d.php', $this->getIndexVersionsPath(), $this->getVersionName());
    }

    /**
     * @return int
     */
    public function getVersionName()
    {
        return $this->versionName;
    }

    public function getIndexVersionName()
    {
        return sprintf('%s_%d', $this->getIndexName(), $this->getVersionName());
    }
}
