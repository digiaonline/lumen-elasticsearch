<?php

namespace Nord\Lumen\Elasticsearch\Search\Scoring\Functions;

/**
 * Class ScriptScoringFunction
 * @package Nord\Lumen\Elasticsearch\Search\Scoring\Functions
 */
class ScriptScoringFunction extends AbstractScoringFunction
{
    /**
     * @var mixed
     */
    private $params;

    /**
     * @var string
     */
    private $inline;

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function getInline()
    {
        return $this->inline;
    }

    /**
     * @param string $inline
     *
     * @return $this
     */
    public function setInline($inline)
    {
        $this->inline = $inline;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $script = [
            'inline' => $this->getInline(),
        ];

        $params = $this->getParams();

        if (!empty($params)) {
            $script['params'] = $this->getParams();
        }

        return [
            'script_score' => [
                'script' => $script
            ]
        ];
    }
}
