<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Compound;

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
     */
    public function toArray()
    {
        return [
            'constant_score' => [
                'filter' => $this->getQuery()->toArray(),
                'boost'  => $this->getBoost(),
            ],
        ];
    }

}
