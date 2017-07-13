<?php

namespace Nord\Lumen\Elasticsearch\Search\Query\Partial;

/**
 * Class RegexpQuery
 * @package Nord\Lumen\Elasticsearch\Search\Query\Partial
 */
class RegexpQuery extends AbstractQuery
{

    /**
     * @inheritdoc
     */
    protected function getQueryType()
    {
        return 'regexp';
    }
}
