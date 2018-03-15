<?php

namespace Nord\Lumen\Elasticsearch\Search\Query;

/**
 * Class BoostMode
 * @package Nord\Lumen\Elasticsearch\Search\Query
 */
class BoostMode
{
    const MODE_MULTIPLY = 'multiply';
    const MODE_REPLACE  = 'replace';
    const MODE_SUM      = 'sum';
    const MODE_AVG      = 'avg';
    const MODE_MAX      = 'max';
    const MODE_MIN      = 'min';
}
