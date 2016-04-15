<?php namespace Nord\Lumen\Elasticsearch\Queries\TermLevel;

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
     * @var string
     */
    private $field;

    /**
     * @var mixed Greater-than or equal to.
     */
    private $gte;

    /**
     * @var mixed Greater-than.
     */
    private $gt;

    /**
     * @var mixed Less-than or equal to.
     */
    private $lte;

    /**
     * @var mixed Less-than.
     */
    private $lt;

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

        $gte = $this->getGreaterThanOrEquals();
        if (!is_null($gte)) {
            $range['gte'] = $gte;
        }
        $gt = $this->getGreaterThan();
        if (!is_null($gt)) {
            $range['gt'] = $gt;
        }
        $lte = $this->getLessThanOrEquals();
        if (!is_null($lte)) {
            $range['lte'] = $lte;
        }
        $lt = $this->getLessThan();
        if (!is_null($lt)) {
            $range['lt'] = $lt;
        }
        $boost = $this->getBoost();
        if (!is_null($boost)) {
            $range['boost'] = $boost;
        }

        return ['range' => [$this->getField() => $range]];
    }


    /**
     * @param string $field
     * @return RangeQuery
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
     * @param mixed $gte
     * @return RangeQuery
     */
    public function setGreaterThanOrEquals($gte)
    {
        $this->gte = $gte;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGreaterThanOrEquals()
    {
        return $this->gte;
    }


    /**
     * @param mixed $gt
     * @return RangeQuery
     */
    public function setGreaterThan($gt)
    {
        $this->gt = $gt;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGreaterThan()
    {
        return $this->gt;
    }


    /**
     * @param mixed $lte
     * @return RangeQuery
     */
    public function setLessThanOrEquals($lte)
    {
        $this->lte = $lte;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLessThanOrEquals()
    {
        return $this->lte;
    }


    /**
     * @param mixed $lt
     * @return RangeQuery
     */
    public function setLessThan($lt)
    {
        $this->lt = $lt;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLessThan()
    {
        return $this->lt;
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
