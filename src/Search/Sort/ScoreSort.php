<?php namespace Nord\Lumen\Elasticsearch\Search\Sort;

/**
 * Allows to add one or more sort on specific fields. Each sort can be reversed as well. The sort is defined on a per
 * field level, with special field name for _score to sort by score, and _doc to sort by index order.
 *
 * The order defaults to desc when sorting on the _score, and defaults to asc when sorting on anything else.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html
 */
class ScoreSort extends AbstractSort
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $options = $this->applyOptions([]);

        if (empty($options)) {
            return '_score';
        } else {
            return ['_score' => $options];
        }
    }
}
