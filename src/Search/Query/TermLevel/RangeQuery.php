<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Matches documents with fields that have terms within a certain range. The type of the Lucene query depends on the
 * field type, for string fields, the TermRangeQuery, while for number/date fields, the query is a NumericRangeQuery.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class RangeQuery extends AbstractQuery
{

    /**
     * @var mixed Greater-than or equal to.
     */
    private $greaterThanOrEqual;

    /**
     * @var mixed Greater-than.
     */
    private $greaterThan;

    /**
     * @var mixed Less-than or equal to.
     */
    private $lessThanOrEqual;

    /**
     * @var mixed Less-than.
     */
    private $lessThan;

    /**
     * @var float Sets the boost value of the query, defaults to 1.0.
     */
    private $boost;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $range = [];

        $greaterThanOrEquals = $this->getGreaterThanOrEquals();
        if (!is_null($greaterThanOrEquals)) {
            $range['gte'] = $greaterThanOrEquals;
        }
        $greaterThan = $this->getGreaterThan();
        if (!is_null($greaterThan)) {
            $range['gt'] = $greaterThan;
        }
        $lessThanOrEquals = $this->getLessThanOrEquals();
        if (!is_null($lessThanOrEquals)) {
            $range['lte'] = $lessThanOrEquals;
        }
        $lessThan = $this->getLessThan();
        if (!is_null($lessThan)) {
            $range['lt'] = $lessThan;
        }
        $boost = $this->getBoost();
        if (!is_null($boost)) {
            $range['boost'] = $boost;
        }

        return ['range' => [$this->getField() => $range]];
    }


    /**
     * @param mixed $greaterThanOrEquals
     * @return RangeQuery
     */
    public function setGreaterThanOrEquals($greaterThanOrEquals)
    {
        $this->greaterThanOrEqual = $greaterThanOrEquals;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGreaterThanOrEquals()
    {
        return $this->greaterThanOrEqual;
    }


    /**
     * @param mixed $greaterThan
     * @return RangeQuery
     */
    public function setGreaterThan($greaterThan)
    {
        $this->greaterThan = $greaterThan;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGreaterThan()
    {
        return $this->greaterThan;
    }


    /**
     * @param mixed $lessThanOrEqual
     * @return RangeQuery
     */
    public function setLessThanOrEquals($lessThanOrEqual)
    {
        $this->lessThanOrEqual = $lessThanOrEqual;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLessThanOrEquals()
    {
        return $this->lessThanOrEqual;
    }


    /**
     * @param mixed $lessThan
     * @return RangeQuery
     */
    public function setLessThan($lessThan)
    {
        $this->lessThan = $lessThan;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLessThan()
    {
        return $this->lessThan;
    }


    /**
     * @param float $boost
     * @return RangeQuery
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
                'Range Query `boost` must be a float value, "%s" given.',
                gettype($boost)
            ));
        }
    }
}
