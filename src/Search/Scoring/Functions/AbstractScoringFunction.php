<?php namespace Nord\Lumen\Elasticsearch\Search\Scoring\Functions;

/**
 * The function_score query provides several types of score functions.
 *
 * - "script_score" function
 * The script_score function allows you to wrap another query and customize the scoring of it optionally with a
 * computation derived from other numeric field values in the doc using a script expression.
 *
 * - "weight" function
 * The weight score allows you to multiply the score by the provided weight. This can sometimes be desired since boost
 * value set on specific queries gets normalized, while for this score function it does not.
 *
 * - "random_score" function
 * The random_score generates scores using a hash of the _uid field, with a seed for variation. If seed is not
 * specified, the current time is used.
 *
 * - "field_value_factor" function
 * The field_value_factor function allows you to use a field from a document to influence the score. It’s similar to
 * using the script_score function, however, it avoids the overhead of scripting. If used on a multi-valued field, only
 * the first value of the field is used in calculations.
 *
 * - "decay" functions: gauss, linear, exp
 * Decay functions score a document with a function that decays depending on the distance of a numeric field value of
 * the document from a user given origin. This is similar to a range query, but with smooth edges instead of boxes.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#score-functions
 * @see \Nord\Lumen\Elasticsearch\Search\Query\Compound\FunctionScoreQuery
 */
abstract class AbstractScoringFunction
{
    /**
     * @return array
     */
    abstract public function toArray();
}
