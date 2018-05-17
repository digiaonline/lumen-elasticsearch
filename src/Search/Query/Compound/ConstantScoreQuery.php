<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasBoost;
use Nord\Lumen\Elasticsearch\Search\Query\Traits\HasQuery;

/**
 * Class ConstantScoreQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\Compound
 */
class ConstantScoreQuery extends AbstractQuery
{
    use HasQuery;
    use HasBoost;

    /**
     * @inheritdoc
     * @throws InvalidArgument
     */
    public function toArray()
    {
        $query = $this->getQuery();

        if ($query === null) {
            throw new InvalidArgument('Query must be set');
        }

        return [
            'constant_score' => [
                'filter' => $query->toArray(),
                'boost'  => $this->getBoost(),
            ],
        ];
    }
}
