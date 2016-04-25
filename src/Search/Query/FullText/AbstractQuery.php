<?php namespace Nord\Lumen\Elasticsearch\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

/**
 * The high-level full text queries are usually used for running full text queries on full text fields like the body of
 * an email. They understand how the field being queried is analyzed and will apply each field’s analyzer
 * (or search_analyzer) to the query string before executing.
 *
 * The queries in this group are:
 *
 * - "match" query
 * The standard query for performing full text queries, including fuzzy matching and phrase or proximity queries.
 *
 * - "multi_match" query
 * The multi-field version of the match query.
 *
 * - "common_terms" query
 * A more specialized query which gives more preference to uncommon words.
 *
 * - "query_string" query
 * Supports the compact Lucene query string syntax, allowing you to specify AND|OR|NOT conditions and multi-field search
 * within a single query string. For expert users only.
 *
 * - "simple_query_string" query
 * A simpler, more robust version of the query_string syntax suitable for exposing directly to users.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/full-text-queries.html
 */
abstract class AbstractQuery extends QueryDSL
{

}
