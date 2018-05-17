<?php namespace Nord\Lumen\Elasticsearch\Search\Query\FullText;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasFields;
use Nord\Lumen\Elasticsearch\Search\Traits\HasTieBreaker;

/**
 * The multi_match query builds on the match query to allow multi-field queries.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatchQuery extends MatchQuery
{
    use HasFields;
    use HasTieBreaker;
    
    public const TYPE_BEST_FIELDS  = 'best_fields';
    public const TYPE_MOST_FIELDS  = 'most_fields';
    public const TYPE_CROSS_FIELDS = 'cross_fields';

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
     * @param array $match
     * @return array
     */
    protected function applyOptions(array $match)
    {
        $multiMatch = parent::applyOptions($match);

        $tieBreaker = $this->getTieBreaker();
        if (null !== $tieBreaker) {
            $multiMatch['tie_breaker'] = $tieBreaker;
        }

        return $multiMatch;
    }
}
