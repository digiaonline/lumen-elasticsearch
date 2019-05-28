<?php

namespace Nord\Lumen\Elasticsearch\Console;

abstract class AbstractMultiIndexCommand extends IndexCommand
{

    /**
     * @return string[] the index names into which the command should index the data
     */
    abstract public function getIndices(): array;

    /**
     * @inheritDoc
     */
    public function getIndex()
    {
        throw new \RuntimeException('This method should be not be used with ' . __CLASS__);
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        foreach ($this->getIndices() as $index) {
            $this->indexData($index);
        }
    }
}
