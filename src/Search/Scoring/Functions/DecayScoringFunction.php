<?php namespace Nord\Lumen\Elasticsearch\Search\Scoring\Functions;

use Nord\Lumen\Elasticsearch\Search\Traits\HasField;

/**
 * Decay functions score a document with a function that decays depending on the distance of a numeric field value of
 * the document from a user given origin. This is similar to a range query, but with smooth edges instead of boxes.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-decay
 */
class DecayScoringFunction extends AbstractScoringFunction
{
    use HasField;
    
    const DECAY_FUNCTION_LINEAR = 'linear';
    const DECAY_FUNCTION_EXPONENTIAL = 'exp';
    const DECAY_FUNCTION_GAUSSIAN = 'gauss';

    /**
     * @var string One of the `DECAY_FUNCTION_` constants.
     */
    private $decayFunction;

    /**
     * @var mixed The point of origin used for calculating distance. Must be given as a number for numeric field, date
     * for date fields and geo point for geo fields. Required for geo and numeric field. For date fields the default is
     * now. Date math (for example now-1h) is supported for origin.
     */
    private $origin;

    /**
     * @var mixed Required for all types. Defines the distance from origin at which the computed score will equal decay
     * parameter. For geo fields: Can be defined as number+unit (1km, 12m,…). Default unit is meters. For date fields:
     * Can to be defined as a number+unit ("1h", "10d",…). Default unit is milliseconds. For numeric field: Any number.
     */
    private $scale;

    /**
     * @var mixed If an offset is defined, the decay function will only compute the decay function for documents with a
     * distance greater that the defined offset. The default is 0.
     */
    private $offset;

    /**
     * @var mixed The decay parameter defines how documents are scored at the distance given at scale. If no decay is
     * defined, documents at the distance scale will be scored 0.5.
     */
    private $decay;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $options = [
            'origin' => $this->getOrigin(),
            'scale'  => $this->getScale(),
        ];
        $offset = $this->getOffset();
        if (!empty($offset)) {
            $options['offset'] = $offset;
        }
        $decay = $this->getDecay();
        if (!empty($decay)) {
            $options['decay'] = $decay;
        }

        return [
            $this->getDecayFunction() => [
                $this->getField() => $options
            ],
        ];
    }


    /**
     * @return string
     */
    public function getDecayFunction()
    {
        return $this->decayFunction;
    }


    /**
     * @param string $decayFunction
     * @return DecayScoringFunction
     */
    public function setDecayFunction($decayFunction)
    {
        $this->decayFunction = $decayFunction;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }


    /**
     * @param mixed $origin
     * @return DecayScoringFunction
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getScale()
    {
        return $this->scale;
    }


    /**
     * @param mixed $scale
     * @return DecayScoringFunction
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }


    /**
     * @param mixed $offset
     * @return DecayScoringFunction
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDecay()
    {
        return $this->decay;
    }


    /**
     * @param mixed $decay
     * @return DecayScoringFunction
     */
    public function setDecay($decay)
    {
        $this->decay = $decay;
        return $this;
    }
}
