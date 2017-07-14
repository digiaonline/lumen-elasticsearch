<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

/**
 * Filters documents that have fields that match any of the provided terms (not analyzed).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class TermsQuery extends AbstractQuery
{

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
