<?php

namespace Nord\Lumen\Elasticsearch\Search\Query;

/**
 * Class ScoreMode
 * @package Nord\Lumen\Elasticsearch\Search\Query
 */
class ScoreMode
{
    const MODE_AVG      = 'avg';
    const MODE_SUM      = 'sum';
    const MODE_MIN      = 'min';
    const MODE_MAX      = 'max';
    const MODE_SCORE    = 'score';
    const MODE_MULTIPLY = 'multiply';
    const MODE_FIRST    = 'first';
    const MODE_NONE     = 'none';
}
