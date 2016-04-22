<?php namespace Nord\Lumen\Elasticsearch\Queries\FullText;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * The multi_match query builds on the match query to allow multi-field queries.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatchQuery extends MatchQuery
{
    const TYPE_BEST_FIELDS   = 'best_fields';
    const TYPE_MOST_FIELDS   = 'most_fields';
    const TYPE_CROSS_FIELDS  = 'cross_fields';

    /**
     * @var array
     */
    private $fields;

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

        $this->applyOptions($multiMatch);

        return ['multi_match' => $multiMatch];
    }


    /**
     * @param array $fields
     * @return MultiMatchQuery
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
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
        $match = parent::applyOptions($match);

        $tieBreaker = $this->getTieBreaker();
        if (!is_null($tieBreaker)) {
            $multiMatch['tie_breaker'] = $tieBreaker;
        }

        return $match;
    }


    /**
     * @param string $type
     * @throws InvalidArgument
     */
    protected function assertType($type)
    {
        $validTypes = [
            self::TYPE_BEST_FIELDS,
            self::TYPE_MOST_FIELDS,
            self::TYPE_CROSS_FIELDS,
            self::TYPE_PHRASE,
            self::TYPE_PHRASE_PREFIX
        ];
        if (!in_array($type, $validTypes)) {
            throw new InvalidArgument(sprintf(
                'MultiMatch Query `type` must be one of "%s", "%s" given.',
                implode(', ', $validTypes),
                $type
            ));
        }
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
