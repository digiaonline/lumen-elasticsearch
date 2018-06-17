<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket;

use Nord\Lumen\Elasticsearch\Search\Traits\HasField;

/**
 * A multi-bucket value source based aggregation where buckets are dynamically built - one per unique value.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
class TermsAggregation extends AbstractAggregation
{
    use HasField;

    /**
     * @var int
     */
    protected $minDocCount;

    /**
     * @return int|null
     */
    public function getMinDocCount(): ?int
    {
        return $this->minDocCount;
    }

    /**
     * @param int $minDocCount
     */
    public function setMinDocCount(int $minDocCount)
    {
        $this->minDocCount = $minDocCount;

        return $this;
    }

    public function toArray()
    {
        $data = [
            'terms' => [
                'field' => $this->getField(),
            ],
        ];

        if (!empty($this->getMinDocCount())) {
            $data['terms']['min_doc_count'] = $this->getMinDocCount();
        }

        return $data;
    }
}
