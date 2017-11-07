<?php namespace Nord\Lumen\Elasticsearch\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasFields;

/**
 * The multi_match query builds on the match query to allow multi-field queries.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatchQuery extends MatchQuery
{
    use HasFields;
    
    const TYPE_BEST_FIELDS   = 'best_fields';
    const TYPE_MOST_FIELDS   = 'most_fields';
    const TYPE_CROSS_FIELDS  = 'cross_fields';

    /**
     * @var float By default, each per-term blended query will use the best score returned by any field in a group,
     * then these scores are added together to give the final score. The tie_breaker parameter can change the default
     * behaviour of the per-term blended queries.
     */
    private $tieBreaker;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $multiMatch = [
            'query'  => $this->getValue(),
            'fields' => $this->getFields()
        ];

        $multiMatch = $this->applyOptions($multiMatch);

        return ['multi_match' => $multiMatch];
    }


    /**
     * @param float $tieBreaker
     * @return MultiMatchQuery
     */
    public function setTieBreaker($tieBreaker)
    {
        $this->assertTieBreaker($tieBreaker);
        $this->tieBreaker = $tieBreaker;
        return $this;
    }


    /**
     * @return float
     */
    public function getTieBreaker()
    {
        return $this->tieBreaker;
    }


    /**
     * @param array $match
     * @return array
     */
    protected function applyOptions(array $match)
    {
        $multiMatch = parent::applyOptions($match);

        $tieBreaker = $this->getTieBreaker();
        if (!is_null($tieBreaker)) {
            $multiMatch['tie_breaker'] = $tieBreaker;
        }

        return $multiMatch;
    }


    /**
     * @param float $tieBreaker
     * @throws InvalidArgument
     */
    protected function assertTieBreaker($tieBreaker)
    {
        if (!is_float($tieBreaker)) {
            throw new InvalidArgument(sprintf(
                'MultiMatch Query `tie_breaker` must be a float value, "%s" given.',
                gettype($tieBreaker)
            ));
        }
    }
}
