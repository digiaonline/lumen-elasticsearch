<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

/**
 * Trait HasFields
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasFields
{

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }
}
