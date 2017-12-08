<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Payloads;

/**
 * Class ApplyMigrationPayload
 * @package Nord\Lumen\Elasticsearch\Pipelines\Payloads
 */
class ApplyMigrationPayload extends MigrationPayload
{

    /**
     * @var string
     */
    private $targetVersionFile;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var bool
     */
    private $force;

    /**
     * @var int
     */
    private $numberOfReplicas;

    /**
     * ApplyMigrationPayload constructor.
     *
     * @param string $configurationPath
     * @param int $batchSize
     * @param bool $force
     */
    public function __construct($configurationPath, $batchSize, $force = false)
    {
        parent::__construct($configurationPath);

        $this->batchSize = $batchSize;

        $this->force = $force;
    }

    /**
     * @param string $targetVersionFile
     */
    public function setTargetVersionFile($targetVersionFile)
    {
        $this->targetVersionFile = $targetVersionFile;
    }

    /**
     * @return string
     */
    public function getTargetVersionPath()
    {
        return sprintf('%s/%s', $this->getIndexVersionsPath(), $this->targetVersionFile);
    }

    /**
     * @return array
     */
    public function getTargetConfiguration()
    {
        $config = include $this->getTargetVersionPath();

        if ($this->force) {
            $config['update_all_types'] = true;
        }

        return $config;
    }

    /**
     * @return string
     */
    public function getTargetVersionName()
    {
        return $this->getTargetConfiguration()['index'];
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return int
     */
    public function getNumberOfReplicas()
    {
        return $this->numberOfReplicas;
    }

    /**
     * @param int $numberOfReplicas
     */
    public function setNumberOfReplicas($numberOfReplicas)
    {
        $this->numberOfReplicas = $numberOfReplicas;
    }
}
