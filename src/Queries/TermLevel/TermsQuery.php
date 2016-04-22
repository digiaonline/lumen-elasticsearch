<?php namespace Nord\Lumen\Elasticsearch\Queries\TermLevel;

/**
 * Filters documents that have fields that match any of the provided terms (not analyzed).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class TermsQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $values;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'terms' => [
                $this->getField() => $this->getValues()
            ]
        ];
    }


    /**
     * @param string $field
     * @return TermsQuery
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
     * @param array $values
     * @return TermsQuery
     */
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }


    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
