<?php

namespace Nord\Lumen\Elasticsearch\Helpers;

/**
 * Class ReindexResponseTransformer
 * @package Nord\Lumen\Elasticsearch\Helpers
 */
class ArrayHelper
{
    /**
     * Transform response array to Symfony Table rows input
     * @param array $response
     *
     * @return array
     */
    public static function arrayToTableRows(array $response)
    {
        $rows = [];
        foreach ($response as $key => $value) {
            if (!is_array($value)) {
                $rows[] = [$key, $value];
            } else {
                $rows[] = [$key, implode(",", $value)];
            }
        }
        return $rows;
    }
}
