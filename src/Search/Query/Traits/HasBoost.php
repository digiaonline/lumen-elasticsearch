<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Trait BoostableQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasBoost
{

    /**
     * @var float Sets the boost value of the query, defaults to 1.0.
     */
    protected $boost;

    /**
     * @return float
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * @param float $boost
     *
     * @return $this
     *
     * @throws InvalidArgument
     */
    public function setBoost($boost)
    {
        $this->assertBoost($boost);
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

    /**
     * @param float $boost
     *
     * @throws InvalidArgument
     */
    protected function assertBoost($boost)
    {
        if (!is_float($boost)) {
            throw new InvalidArgument(sprintf(
                '`boost` must be a float value, "%s" given.',
                gettype($boost)
            ));
        }
    }
}
