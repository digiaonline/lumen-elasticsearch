<?php

namespace Nord\Lumen\Elasticsearch\Helpers;

/**
 * Class ReindexResponseTransformer
 * @package Nord\Lumen\Elasticsearch\Helpers
 */
class ArrayHelper
{
    /**
     * Transform response array to symfony table rows input
     * @param array $response
     *
     * @return array
     */
    public static function toTableRowsInput(array $response)
    {
        $rows = [];
        foreach ($response as $key => $value) {
            if (is_array($value)) {
                $rows[] = [$key, count($value) ? http_build_query($value, '', ',') : '[]'];
            } elseif (is_bool($value)) {
                $rows[] = [$key, $value ? 'true' : 'false'];
            } else {
                $rows[] = [$key, strval($value)];
            }
        }
        return $rows;
    }
}
