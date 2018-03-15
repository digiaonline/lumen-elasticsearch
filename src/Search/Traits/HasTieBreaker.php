<?php

namespace Nord\Lumen\Elasticsearch\Search\Traits;

/**
 * Trait HasTieBreaker
 * @package Nord\Lumen\Elasticsearch\Search\Traits
 */
trait HasTieBreaker
{

    /**
     * @var ?float
     */
    protected $tieBreaker;

    /**
     * @param float $tieBreaker
     *
     * @return $this
     */
    public function setTieBreaker(float $tieBreaker)
    {
        $this->tieBreaker = $tieBreaker;

        return $this;
    }

    /**
     * @return float
     */
    public function getTieBreaker()
    {
        return $this->tieBreaker;
    }
}
