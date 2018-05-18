<?php namespace Nord\Lumen\Elasticsearch\Search;

use Illuminate\Contracts\Support\Arrayable;
use Nord\Lumen\Elasticsearch\Search\Sort\AbstractSort;

/**
 * Allows to add one or more sort on specific fields. Each sort can be reversed as well. The sort is defined on a per
 * field level, with special field name for _score to sort by score, and _doc to sort by index order.
 *
 * The order defaults to desc when sorting on the _score, and defaults to asc when sorting on anything else.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html
 */
class Sort implements Arrayable
{
    /**
     * @var AbstractSort[]
     */
    private $sorts;

    /**
     * Sort constructor.
     *
     * @param AbstractSort[] $sorts
     */
    public function __construct(array $sorts = [])
    {
        $this->sorts = $sorts;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->getSorts() as $sort) {
            $result[] = $sort->toArray();
        }

        return $result;
    }


    /**
     * @param AbstractSort $sort
     * @return $this
     */
    public function addSort(AbstractSort $sort)
    {
        $this->sorts[] = $sort;
        return $this;
    }


    /**
     * @param AbstractSort[] $sorts
     * @return Sort
     */
    public function setSorts($sorts)
    {
        $this->sorts = $sorts;
        return $this;
    }


    /**
     * @return AbstractSort[]
     */
    public function getSorts()
    {
        return $this->sorts;
    }
}
