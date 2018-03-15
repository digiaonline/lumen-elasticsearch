<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

/**
 * Trait BoostableQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasBoost
{

    /**
     * @var ?float Sets the boost value of the query, defaults to 1.0.
     */
    protected $boost;

    /**
     * @return float|null
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * @param float $boost
     *
     * @return $this
     */
    public function setBoost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasBoost()
    {
        return $this->boost !== null;
    }
}
