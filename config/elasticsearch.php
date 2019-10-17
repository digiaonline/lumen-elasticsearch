<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch host urls.
    |--------------------------------------------------------------------------
    |
    | Set the hosts to use for connecting to elasticsearch.
    |
    */

    'hosts'        => ['localhost:9200'],

    /*
     * The prefix to use for index names
     */
    'index_prefix' => env('ELASTICSEARCH_INDEX_PREFIX'),
];
