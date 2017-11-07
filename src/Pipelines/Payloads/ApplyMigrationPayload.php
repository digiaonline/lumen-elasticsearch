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
     * @var int
     */
    private $numberOfReplicas;

    /**
     * ApplyMigrationPayload constructor.
     *
     * @param string $configurationPath
     * @param int    $batchSize
     */
    public function __construct($configurationPath, $batchSize)
    {
        parent::__construct($configurationPath);

        $this->batchSize = $batchSize;
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
        return include $this->getTargetVersionPath();
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
