<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Trait HasScoreMode
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasScoreMode
{

    /**
     * @var string
     */
    protected $scoreMode;

    /**
     * @return array
     */
    abstract protected function getValidScoreModes();

    /**
     * @return string
     */
    public function getScoreMode()
    {
        return $this->scoreMode;
    }

    /**
     * @param string $scoreMode
     *
     * @return $this
     */
    public function setScoreMode($scoreMode)
    {
        $this->assertScoreMode($scoreMode);
        $this->scoreMode = $scoreMode;

        return $this;
    }

    /**
     * @param string $scoreMode
     *
     * @throws InvalidArgument
     */
    protected function assertScoreMode($scoreMode)
    {
        $validModes = $this->getValidScoreModes();

        if (!in_array($scoreMode, $validModes)) {
            throw new InvalidArgument(sprintf(
                '`score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
    }
}
