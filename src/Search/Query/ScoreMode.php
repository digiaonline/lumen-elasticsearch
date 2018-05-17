<?php

namespace Nord\Lumen\Elasticsearch\Search\Query;

/**
 * Class ScoreMode
 * @package Nord\Lumen\Elasticsearch\Search\Query
 */
class ScoreMode
{
    public const MODE_AVG      = 'avg';
    public const MODE_SUM      = 'sum';
    public const MODE_MIN      = 'min';
    public const MODE_MAX      = 'max';
    public const MODE_SCORE    = 'score';
    public const MODE_MULTIPLY = 'multiply';
    public const MODE_FIRST    = 'first';
    public const MODE_NONE     = 'none';
}
