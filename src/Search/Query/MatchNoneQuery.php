<?php

namespace Nord\Lumen\Elasticsearch\Search\Query;

class MatchNoneQuery extends QueryDSL
{
    public function toArray(): array
    {
        // See https://github.com/elastic/elasticsearch-php/issues/495
        return ['match_none' => new \stdClass()];
    }
}
