<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

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
        $this->scoreMode = $scoreMode;

        return $this;
    }
}
