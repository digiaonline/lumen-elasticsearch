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

    public function toArray()
    {
        return [
            'terms' => [
                'field' => $this->getField()
            ]
        ];
    }
}
