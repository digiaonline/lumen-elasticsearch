<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
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
    use HasBoost;
    use HasQuery;
    use HasScoreMode;

    /**
     * @var AbstractScoringFunction[]
     */
    private $functions = [];

    /**
     * @var float|null
     */
    private $weight;

    /**
     * @var float|null
     */
    private $maxBoost;

    /**
     * @var string|null
     */
    private $boostMode;

    /**
     * @var int|float|null
     */
    private $minScore;

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
                $array['query'] = ['match_all' => new \stdClass()];
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
        if ($scoreMode !== null) {
            $array['score_mode'] = $scoreMode;
        }

        if ($this->hasWeight()) {
            $array['weight'] = $this->getWeight();
        }

        if ($this->hasBoost()) {
            $array['boost'] = $this->getBoost();
        }

        if ($this->hasMaxBoost()) {
            $array['max_boost'] = $this->getMaxBoost();
        }

        if ($this->hasBoostMode()) {
            $array['boost_mode'] = $this->getBoostMode();
        }

        if ($this->hasMinScore()) {
            $array['min_score'] = $this->getMinScore();
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
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return bool
     */
    public function hasWeight()
    {
        return $this->getWeight() !== null;
    }

    /**
     * @param float $weight
     *
     * @return FunctionScoreQuery
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getMaxBoost()
    {
        return $this->maxBoost;
    }

    /**
     * @return bool
     */
    public function hasMaxBoost()
    {
        return $this->getMaxBoost() !== null;
    }

    /**
     * @param float $maxBoost
     *
     * @return FunctionScoreQuery
     */
    public function setMaxBoost($maxBoost)
    {
        $this->maxBoost = $maxBoost;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBoostMode()
    {
        return $this->boostMode;
    }

    /**
     * @return bool
     */
    public function hasBoostMode()
    {
        return $this->getBoostMode() !== null;
    }

    /**
     * @param string $boostMode
     *
     * @return FunctionScoreQuery
     */
    public function setBoostMode($boostMode)
    {
        $this->boostMode = $boostMode;

        return $this;
    }

    /**
     * @return int|float|null
     */
    public function getMinScore()
    {
        return $this->minScore;
    }

    /**
     * @return bool
     */
    public function hasMinScore()
    {
        return $this->getMinScore() !== null;
    }

    /**
     * @param int|float $minScore
     *
     * @return FunctionScoreQuery
     */
    public function setMinScore($minScore)
    {
        $this->minScore = $minScore;

        return $this;
    }
}
