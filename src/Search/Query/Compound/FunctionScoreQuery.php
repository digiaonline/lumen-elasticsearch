<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasScoreMode;
use Nord\Lumen\Elasticsearch\Search\Scoring\Functions\AbstractScoringFunction;

/**
 * The function_score allows you to modify the score of documents that are retrieved by a query. This can be useful if,
 * for example, a score function is computationally expensive and it is sufficient to compute the score on a filtered
 * set of documents.
 *
 * To use function_score, the user has to define a query and one or more functions, that compute a new score for each
 * document returned by the query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html
 */
class FunctionScoreQuery extends AbstractQuery
{
    use HasQuery;
    use HasScoreMode;

    /**
     * @var AbstractScoringFunction[]
     */
    private $functions = [];

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $array = [];

        $query = $this->getQuery();
        if ($query !== null) {
            $queryArray = $query->toArray();
            if (!empty($queryArray)) {
                $array['query'] = $queryArray;
            } else {
                $array['query'] = ['match_all' => []];
            }
        }

        $functions = [];
        foreach ($this->getFunctions() as $function) {
            $functions[] = $function->toArray();
        }

        if (!empty($functions)) {
            $array['functions'] = $functions;
        }

        $scoreMode = $this->getScoreMode();
        if (!empty($scoreMode)) {
            $array['score_mode'] = $scoreMode;
        }

        return ['function_score' => $array];
    }

    /**
     * @return AbstractScoringFunction[]
     */
    public function getFunctions()
    {
        return $this->functions;
    }


    /**
     * @param AbstractScoringFunction[] $functions
     * @return FunctionScoreQuery
     */
    public function setFunctions(array $functions)
    {
        $this->functions = [];
        foreach ($functions as $function) {
            $this->addFunction($function);
        }
        return $this;
    }


    /**
     * @param AbstractScoringFunction $function
     * @return FunctionScoreQuery
     */
    public function addFunction(AbstractScoringFunction $function)
    {
        $this->functions[] = $function;
        return $this;
    }
}
