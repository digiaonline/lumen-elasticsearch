<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasValue;

/**
 * Class RegexpQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\TermLevel
 *
 * @see     https://www.elastic.co/guide/en/elasticsearch/reference/2.4/query-dsl-regexp-query.html
 */
class RegexpQuery extends AbstractQuery
{
    use HasBoost;
    use HasValue;

    public const FLAG_ALL          = 'ALL';
    public const FLAG_ANYSTRING    = 'ANYSTRING';
    public const FLAG_COMPLEMENT   = 'COMPLEMENT';
    public const FLAG_EMPTY        = 'EMPTY';
    public const FLAG_INTERSECTION = 'INTERSECTION';
    public const FLAG_INTERVAL     = 'INTERVAL';
    public const FLAG_NONE         = 'NONE';

    /**
     * @var array
     */
    private $flags = [];

    /**
     * @var int
     */
    private $maxDeterminizedStates;

    /**
     * @inheritdoc
     *
     * @throws InvalidArgument
     */
    public function toArray()
    {
        if ($this->field === null || $this->value === null) {
            throw new InvalidArgument('"field" and "value" must be set for this type of query');
        }

        $definition = [
            'value' => $this->getValue(),
        ];

        if ($this->hasBoost()) {
            $definition['boost'] = $this->getBoost();
        }

        if (!empty($this->flags)) {
            $definition['flags'] = $this->getFlagsValue();
        }

        if ($this->maxDeterminizedStates !== null) {
            $definition['max_determinized_states'] = $this->maxDeterminizedStates;
        }

        return [
            'regexp' => [
                $this->getField() => $definition,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param array $flags
     *
     * @return RegexpQuery
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * @return string
     */
    protected function getFlagsValue()
    {
        return implode('|', $this->flags);
    }

    /**
     * @return int
     */
    public function getMaxDeterminizedStates()
    {
        return $this->maxDeterminizedStates;
    }

    /**
     * @param int $maxDeterminizedStates
     *
     * @return RegexpQuery
     */
    public function setMaxDeterminizedStates(int $maxDeterminizedStates)
    {
        $this->maxDeterminizedStates = $maxDeterminizedStates;

        return $this;
    }
}
