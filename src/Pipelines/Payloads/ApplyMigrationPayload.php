<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Payloads;

use Nord\Lumen\Elasticsearch\IndexNamePrefixer;

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
    private $updateAllTypes;

    /**
     * @var int
     */
    private $numberOfReplicas;

    /**
     * ApplyMigrationPayload constructor.
     *
     * @param string $configurationPath
     * @param int    $batchSize
     * @param bool   $updateAllTypes
     */
    public function __construct($configurationPath, $batchSize, $updateAllTypes = false)
    {
        parent::__construct($configurationPath);

        $this->batchSize      = $batchSize;
        $this->updateAllTypes = $updateAllTypes;
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

        if ($this->updateAllTypes) {
            $config['update_all_types'] = true;
        }

        return $config;
    }

    /**
     * @return array
     */
    public function getPrefixedTargetConfiguration(): array
    {
        return IndexNamePrefixer::getPrefixedIndexParameters($this->getTargetConfiguration());
    }

    /**
     * @return string
     */
    public function getTargetVersionName()
    {
        return $this->getTargetConfiguration()['index'];
    }

    /**
     * @return string
     */
    public function getPrefixedTargetVersionName(): string
    {
        return IndexNamePrefixer::getPrefixedIndexName($this->getTargetVersionName());
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
