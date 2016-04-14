<?php namespace Nord\Lumen\Elasticsearch\Query\FullText;

/**
 * A family of match queries that accepts text/numerics/dates, analyzes them, and constructs a query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class MatchQuery extends AbstractQuery
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    private $field;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return ['match' => [$this->getField() => $this->value]];
    }


    /**
     * @param mixed $value
     * @return MatchQuery
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @param string $field
     * @return MatchQuery
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}