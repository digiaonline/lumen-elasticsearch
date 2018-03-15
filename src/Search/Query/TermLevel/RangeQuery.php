<?php namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;

/**
 * Matches documents with fields that have terms within a certain range. The type of the Lucene query depends on the
 * field type, for string fields, the TermRangeQuery, while for number/date fields, the query is a NumericRangeQuery.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class RangeQuery extends AbstractQuery
{
    use HasBoost;

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
     * @inheritdoc
     */
    public function toArray()
    {
        $range = [];

        $greaterThanOrEquals = $this->getGreaterThanOrEquals();
        if (null !== $greaterThanOrEquals) {
            $range['gte'] = $greaterThanOrEquals;
        }
        $greaterThan = $this->getGreaterThan();
        if (null !== $greaterThan) {
            $range['gt'] = $greaterThan;
        }
        $lessThanOrEquals = $this->getLessThanOrEquals();
        if (null !== $lessThanOrEquals) {
            $range['lte'] = $lessThanOrEquals;
        }
        $lessThan = $this->getLessThan();
        if (null !== $lessThan) {
            $range['lt'] = $lessThan;
        }
        $boost = $this->getBoost();
        if (null !== $boost) {
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
}
