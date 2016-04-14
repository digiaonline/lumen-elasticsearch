<?php namespace Nord\Lumen\Elasticsearch\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Query\QueryDSL;

/**
 * While the full text queries will analyze the query string before executing, the term-level queries operate on the
 * exact terms that are stored in the inverted index.
 *
 * These queries are usually used for structured data like numbers, dates, and enums, rather than full text fields.
 * Alternatively, they allow you to craft low-level queries, foregoing the analysis process.
 *
 * The queries in this group are:
 *
 * - "term" query
 * Find documents which contain the exact term specified in the field specified.
 *
 * - "terms" query
 * Find documents which contain any of the exact terms specified in the field specified.
 *
 * - "range" query
 * Find documents where the field specified contains values (dates, numbers, or strings) in the range specified.
 *
 * - "exists" query
 * Find documents where the field specified contains any non-null value.
 *
 * - "missing" query
 * Find documents where the field specified does is missing or contains only null values.
 *
 * - "prefix" query
 * Find documents where the field specified contains terms which being with the exact prefix specified.
 *
 * - "wildcard" query
 * Find documents where the field specified contains terms which match the pattern specified, where the pattern supports
 * single character wildcards (?) and multi-character wildcards (*)
 *
 * - "regexp" query
 * Find documents where the field specified contains terms which match the regular expression specified.
 *
 * - "fuzzy" query
 * Find documents where the field specified contains terms which are fuzzily similar to the specified term.
 * Fuzziness is measured as a Levenshtein edit distance of 1 or 2.
 *
 * - "type" query
 * Find documents of the specified type.
 *
 * - "ids" query
 * Find documents with the specified type and IDs.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/term-level-queries.html
 */
abstract class AbstractQuery extends QueryDSL
{

}
