<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Partial;

/**
 * Class WildcardQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\Partial
 */
class WildcardQuery extends AbstractQuery
{

    /**
     * @inheritdoc
     */
    protected function getQueryType()
    {
        return 'wildcard';
    }
}
