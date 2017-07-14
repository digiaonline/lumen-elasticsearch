<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
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
    
    const SCORE_MODE_MULTIPLY = 'multiply';
    const SCORE_MODE_SUM = 'sum';
    const SCORE_MODE_AVG = 'avg';
    const SCORE_MODE_FIRST = 'first';
    const SCORE_MODE_MAX = 'max';
    const SCORE_MODE_MIN = 'min';

    /**
     * @var AbstractScoringFunction[]
     */
    private $functions = [];

    /**
     * @var string One of the `SCORE_MODE_` constants. Default is `multiply`.
     */
    private $scoreMode;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $array = [];

        $query = $this->getQuery();
        if (!empty($query)) {
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


    /**
     * @return string
     */
    public function getScoreMode()
    {
        return $this->scoreMode;
    }


    /**
     * @param string $scoreMode
     * @return FunctionScoreQuery
     */
    public function setScoreMode($scoreMode)
    {
        $this->assertScoreMode($scoreMode);
        $this->scoreMode = $scoreMode;
        return $this;
    }


    /**
     * @param string $scoreMode
     * @throws InvalidArgument
     */
    protected function assertScoreMode($scoreMode)
    {
        $validModes = [
            self::SCORE_MODE_MULTIPLY,
            self::SCORE_MODE_SUM,
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_FIRST,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_MIN
        ];
        if (!in_array($scoreMode, $validModes)) {
            throw new InvalidArgument(sprintf(
                'FunctionScoreQuery `score_mode` must be one of "%s", "%s" given.',
                implode(', ', $validModes),
                $scoreMode
            ));
        }
    }
}
