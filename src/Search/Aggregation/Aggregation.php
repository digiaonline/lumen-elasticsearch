<?php namespace Nord\Lumen\Elasticsearch\Search\Aggregation;

use Illuminate\Contracts\Support\Arrayable;

/**
 * The aggregations framework helps provide aggregated data based on a search query. It is based on simple building
 * blocks called aggregations, that can be composed in order to build complex summaries of the data.
 *
 * An aggregation can be seen as a unit-of-work that builds analytic information over a set of documents. The context of
 * the execution defines what this document set is (e.g. a top-level aggregation executes within the context of the
 * executed query/filters of the search request).
 *
 * There are many different types of aggregations, each with its own purpose and output. To better understand these
 * types, it is often easier to break them into three main families:
 *
 * - "Bucketing"
 * A family of aggregations that build buckets, where each bucket is associated with a key and a document criterion.
 * When the aggregation is executed, all the buckets criteria are evaluated on every document in the context and when a
 * criterion matches, the document is considered to "fall in" the relevant bucket. By the end of the aggregation
 * process, we’ll end up with a list of buckets - each one with a set of documents that "belong" to it.
 *
 * - "Metric"
 * Aggregations that keep track and compute metrics over a set of documents.
 *
 * - "Pipeline"
 * Aggregations that aggregate the output of other aggregations and their associated metrics
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations.html
 */
abstract class Aggregation implements Arrayable
{

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
