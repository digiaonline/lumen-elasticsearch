<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Traits;

/**
 * Trait HasType
 * @package Nord\Lumen\Elasticsearch\Search\Query\Traits
 */
trait HasType
{

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
