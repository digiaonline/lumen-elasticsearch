<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Class RegexpQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\TermLevel
 */
class RegexpQuery extends AbstractQuery
{

    use BoostableQuery;

    const FLAG_ALL          = 'ALL';
    const FLAG_ANYSTRING    = 'ANYSTRING';
    const FLAG_COMPLEMENT   = 'COMPLEMENT';
    const FLAG_EMPTY        = 'EMPTY';
    const FLAG_INTERSECTION = 'INTERSECTION';
    const FLAG_INTERVAL     = 'INTERVAL';
    const FLAG_NONE         = 'NONE';

    /**
     * @var string
     */
    private $value;

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
        if (!isset($this->field) || !isset($this->value)) {
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
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
     *
     * @throws InvalidArgument
     */
    public function setMaxDeterminizedStates($maxDeterminizedStates)
    {
        if (!is_int($maxDeterminizedStates)) {
            throw new InvalidArgument('max_determinized_states must be an integer');
        }

        $this->maxDeterminizedStates = $maxDeterminizedStates;

        return $this;
    }

}
