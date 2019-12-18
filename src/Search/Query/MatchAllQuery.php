<?php

namespace Nord\Lumen\Elasticsearch\Search\Query;

use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;

class MatchAllQuery extends QueryDSL
{
    use HasBoost;

    public function toArray(): array
    {
        if ($this->getBoost() !== null) {
            return [
                'match_all' => ['boost' => $this->getBoost()],
            ];
        }

        // See https://github.com/elastic/elasticsearch-php/issues/495
        return ['match_all' => new \stdClass()];
    }
}
