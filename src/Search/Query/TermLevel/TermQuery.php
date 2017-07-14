<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

/**
 * The term query finds documents that contain the exact term specified in the inverted index.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class TermQuery extends AbstractQuery
{
    
    use BoostableQuery;

    /**
     * @var mixed
     */
    private $value;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $term = ['value' => $this->getValue()];

        $boost = $this->getBoost();
        if (!is_null($boost)) {
            $term['boost'] = $boost;
        }

        if (count($term) === 1 && isset($term['value'])) {
            $term = $term['value'];
        }

        return ['term' => [$this->getField() => $term]];
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
