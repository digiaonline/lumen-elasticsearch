<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

/**
 * Compound queries wrap other compound or leaf queries, either to combine their results and scores,
 * to change their behaviour, or to switch from query to filter context.
 *
 * The queries in this group are:
 *
 * - "constant_score" query
 * A query which wraps another query, but executes it in filter context.
 * All matching documents are given the same “constant” _score.
 *
 * - "bool" query
 * The default query for combining multiple leaf or compound query clauses, as must, should, must_not, or filter clauses.
 * The must and should clauses have their scores combined — the more matching clauses, the better — while the must_not
 * and filter clauses are executed in filter context.
 *
 * - "dis_max" query
 * A query which accepts multiple queries, and returns any documents which match any of the query clauses.
 * While the bool query combines the scores from all matching queries, the dis_max query uses the score of the single
 * best- matching query clause.
 *
 * - "function_score" query
 * Modify the scores returned by the main query with functions to take into account factors like popularity, recency,
 * distance, or custom algorithms implemented with scripting.
 *
 * - "boosting" query
 * Return documents which match a positive query, but reduce the score of documents which also match a negative query.
 *
 * - "indices" query
 * Execute one query for the specified indices, and another for other indices.
 *
 * - "and, or, not"
 * Synonyms for the bool query.
 *
 * - "filtered" query
 * Combine a query clause in query context with another in filter context.
 *
 * - "limit" query
 * Limits the number of documents examined per shard.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/compound-queries.html
 */
abstract class AbstractQuery extends QueryDSL
{
}
