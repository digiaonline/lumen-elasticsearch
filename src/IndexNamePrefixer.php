<?php

namespace Nord\Lumen\Elasticsearch;

class IndexNamePrefixer
{

    public static function getPrefixedIndexName(string $indexName): string
    {
        $prefix = \getenv('ELASTICSEARCH_INDEX_PREFIX');

        if ($prefix) {
            return \sprintf('%s_%s', $prefix, $indexName);
        }

        return $indexName;
    }

    public static function getPrefixedIndexParameters(array $parameters): array
    {
        if (isset($parameters['index'])) {
            $parameters['index'] = self::getPrefixedIndexName($parameters['index']);
        }

        return $parameters;
    }
}
