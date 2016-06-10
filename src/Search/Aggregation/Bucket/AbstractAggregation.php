<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation\Bucket;

use Nord\Lumen\Elasticsearch\Search\Aggregation\Aggregation;

/**
 * Bucket aggregations don’t calculate metrics over fields like the metrics aggregations do, but instead, they create
 * buckets of documents. Each bucket is associated with a criterion (depending on the aggregation type) which determines
 * whether or not a document in the current context "falls" into it. In other words, the buckets effectively define
 * document sets. In addition to the buckets themselves, the bucket aggregations also compute and return the number of
 * documents that "fell into" each bucket.
 *
 * Bucket aggregations, as opposed to metrics aggregations, can hold sub-aggregations. These sub-aggregations will be
 * aggregated for the buckets created by their "parent" bucket aggregation.
 *
 * There are different bucket aggregators, each with a different "bucketing" strategy. Some define a single bucket, some
 * define fixed number of multiple buckets, and others dynamically create the buckets during the aggregation process.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket.html
 */
abstract class AbstractAggregation extends Aggregation
{
}
