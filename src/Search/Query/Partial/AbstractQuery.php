<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Partial;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

/**
 * @package Nord\Lumen\Elasticsearch\Search\Query\Partial
 * @see     https://www.elastic.co/guide/en/elasticsearch/guide/current/_wildcard_and_regexp_queries.html
 */
abstract class AbstractQuery extends QueryDSL
{

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    abstract protected function getQueryType();

    /**
     * @inheritdoc
     *
     * @throws InvalidArgument
     */
    public function toArray()
    {
        if (!isset($this->field) || !isset($this->value)) {
            throw new InvalidArgument('"field" and "value" must be set for this type of query');
        }

        return [
            $this->getQueryType() => [
                $this->getField() => $this->getValue(),
            ],
        ];
    }

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
     * @return AbstractQuery
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return AbstractQuery
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
