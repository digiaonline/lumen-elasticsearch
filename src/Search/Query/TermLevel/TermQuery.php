<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * The term query finds documents that contain the exact term specified in the inverted index.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class TermQuery extends AbstractQuery
{

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var float A boost can be specified to give this term query a higher relevance score than another query.
     */
    private $boost;


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


    /**
     * @param float $boost
     * @return TermQuery
     * @throws InvalidArgument
     */
    public function setBoost($boost)
    {
        $this->assertBoost($boost);
        $this->boost = $boost;
        return $this;
    }


    /**
     * @return float
     */
    public function getBoost()
    {
        return $this->boost;
    }


    /**
     * @param float $boost
     * @throws InvalidArgument
     */
    protected function assertBoost($boost)
    {
        if (!is_float($boost)) {
            throw new InvalidArgument(sprintf(
                'Term Query `boost` must be a float value, "%s" given.',
                gettype($boost)
            ));
        }
    }
}
