<?php namespace Nord\Lumen\Elasticsearch\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Search\Traits\HasField;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasType;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;

/**
 * A family of match queries that accepts text/numerics/dates, analyzes them, and constructs a query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class MatchQuery extends AbstractQuery
{
    use HasField;
    use HasType;
    use HasValue;
    
    public const OPERATOR_OR  = 'or';
    public const OPERATOR_AND = 'and';

    public const ZERO_TERM_QUERY_NONE = 'none';
    public const ZERO_TERM_QUERY_ALL  = 'all';

    public const TYPE_PHRASE        = 'phrase';
    public const TYPE_PHRASE_PREFIX = 'phrase_prefix';

    /**
     * @var string The operator flag can be set to "or" or "and" to control the boolean clauses (defaults to "or").
     */
    private $operator;

    /**
     * @var string If the analyzer used removes all tokens in a query like a "stop" filter does, the default behavior
     * is to match no documents at all. In order to change that the zero_terms_query option can be used, which accepts
     * "none" (default) and "all" which corresponds to a "match_all" query.
     */
    private $zeroTermsQuery;

    /**
     * @var float The match query supports a cutoff_frequency that allows specifying an absolute or relative document
     * frequency where high frequency terms are moved into an optional subquery and are only scored if one of the low
     * frequency (below the cutoff) terms in the case of an or operator or all of the low frequency terms in the case
     * of an and operator match.
     */
    private $cutOffFrequency;

    /**
     * @var int A phrase query matches terms up to a configurable slop (which defaults to 0) in any order.
     * Transposed terms have a slop of 2.
     */
    private $slop;

    /**
     * @var int A "phrase_prefix" query option that controls to how many prefixes the last term will be expanded.
     * It is highly recommended to set it to an acceptable value to control the execution time of the query.
     */
    private $maxExpansions;

    /**
     * @var string The analyzer can be set to control which analyzer will perform the analysis process on the text. It
     * defaults to the field explicit mapping definition, or the default search analyzer,
     */
    private $analyzer;

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $match = ['query' => $this->getValue()];

        $match = $this->applyOptions($match);

        if (count($match) === 1 && isset($match['query'])) {
            $match = $match['query'];
        }

        return ['match' => [$this->getField() => $match]];
    }


    /**
     * @param string $operator
     * @return MatchQuery
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }


    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }


    /**
     * @param string $zeroTermsQuery
     * @return MatchQuery
     */
    public function setZeroTermsQuery($zeroTermsQuery)
    {
        $this->zeroTermsQuery = $zeroTermsQuery;
        return $this;
    }


    /**
     * @return string
     */
    public function getZeroTermsQuery()
    {
        return $this->zeroTermsQuery;
    }


    /**
     * @param float $cutOffFrequency
     * @return MatchQuery
     */
    public function setCutOffFrequency(float $cutOffFrequency)
    {
        $this->cutOffFrequency = $cutOffFrequency;
        return $this;
    }


    /**
     * @return float
     */
    public function getCutOffFrequency()
    {
        return $this->cutOffFrequency;
    }


    /**
     * @param string $type
     * @return MatchQuery
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @param int $slop
     * @return MatchQuery
     */
    public function setSlop(int $slop)
    {
        $this->slop = $slop;
        return $this;
    }


    /**
     * @return int
     */
    public function getSlop()
    {
        return $this->slop;
    }


    /**
     * @param int $maxExpansions
     * @return MatchQuery
     */
    public function setMaxExpansions(int $maxExpansions)
    {
        $this->maxExpansions = $maxExpansions;
        return $this;
    }


    /**
     * @return int
     */
    public function getMaxExpansions()
    {
        return $this->maxExpansions;
    }


    /**
     * @param string $analyzer
     * @return MatchQuery
     */
    public function setAnalyzer($analyzer)
    {
        $this->analyzer = $analyzer;
        return $this;
    }


    /**
     * @return string
     */
    public function getAnalyzer()
    {
        return $this->analyzer;
    }


    /**
     * @param array $match
     * @return array
     */
    protected function applyOptions(array $match)
    {
        $operator = $this->getOperator();
        if (null !== $operator) {
            $match['operator'] = $operator;
        }
        $zeroTermsQuery = $this->getZeroTermsQuery();
        if (null !== $zeroTermsQuery) {
            $match['zero_terms_query'] = $zeroTermsQuery;
        }
        $cutOffFreq = $this->getCutOffFrequency();
        if (null !== $cutOffFreq) {
            $match['cutoff_frequency'] = $cutOffFreq;
        }
        $type = $this->getType();
        if (null !== $type) {
            $match['type'] = $type;
            $slop = $this->getSlop();
            if (null !== $slop) {
                $match['slop'] = $slop;
            }
            if ($match['type'] === self::TYPE_PHRASE_PREFIX) {
                $maxExp = $this->getMaxExpansions();
                if (null !== $maxExp) {
                    $match['max_expansions'] = $maxExp;
                }
            }
        }
        $analyzer = $this->getAnalyzer();
        if (null !== $analyzer) {
            $match['analyzer'] = $analyzer;
        }

        return $match;
    }
}
