<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

/**
 * Trait HasValue
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasValue
{

    /**
     * @var mixed the value to query for.
     */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
