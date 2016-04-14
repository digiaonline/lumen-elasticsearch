<?php namespace Nord\Lumen\Elasticsearch\Queries\TermLevel;

/**
 * The term query finds documents that contain the exact term specified in the inverted index.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class TermQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return ['term' => [$this->field => $this->value]];
    }


    /**
     * @param string $field
     * @return TermQuery
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


    /**
     * @param mixed $value
     * @return TermQuery
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
}
