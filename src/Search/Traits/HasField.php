<?php

namespace Nord\Lumen\Elasticsearch\Search\Traits;

/**
 * Trait HasField
 * @package Nord\Lumen\Elasticsearch\Search\Traits
 */
trait HasField
{

    /**
     * @var string The field in the index to query on.
     */
    protected $field;

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
