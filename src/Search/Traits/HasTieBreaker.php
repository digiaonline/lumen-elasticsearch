<?php

namespace Nord\Lumen\Elasticsearch\Search\Traits;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Trait HasTieBreaker
 * @package Nord\Lumen\Elasticsearch\Search\Traits
 */
trait HasTieBreaker
{

    /**
     * @var float
     */
    protected $tieBreaker;

    /**
     * @param float $tieBreaker
     *
     * @return $this
     *
     * @throws InvalidArgument
     */
    public function setTieBreaker($tieBreaker)
    {
        $this->assertTieBreaker($tieBreaker);
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

    /**
     * @param float $tieBreaker
     *
     * @throws InvalidArgument
     */
    protected function assertTieBreaker($tieBreaker)
    {
        if (!is_float($tieBreaker)) {
            throw new InvalidArgument(sprintf(
                'MultiMatch Query `tie_breaker` must be a float value, "%s" given.',
                gettype($tieBreaker)
            ));
        }
    }
}
