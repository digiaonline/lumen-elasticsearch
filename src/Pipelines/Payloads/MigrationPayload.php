<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Payloads;

/**
 * Class MigrationPayload
 * @package Nord\Lumen\Elasticsearch\Pipelines\Payloads
 */
abstract class MigrationPayload
{

    /**
     * @var string
     */
    protected $configurationPath;

    /**
     * MigrationPayload constructor.
     *
     * @param string $configurationPath
     */
    public function __construct($configurationPath)
    {
        $this->configurationPath = $configurationPath;
    }

    /**
     * @return string
     */
    public function getConfigurationPath()
    {
        return $this->configurationPath;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return include $this->configurationPath;
    }

    /**
     * @return string
     */
    protected function getConfigurationBasePath()
    {
        return dirname($this->configurationPath);
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->getConfiguration()['index'];
    }

    /**
     * @return string
     */
    public function getIndexVersionsPath()
    {
        return sprintf('%s/versions/%s', $this->getConfigurationBasePath(), $this->getIndexName());
    }
}
