<?php namespace Nord\Lumen\Elasticsearch\Search\Sort;

/**
 * Allows to add one or more sort on specific fields. Each sort can be reversed as well. The sort is defined on a per
 * field level, with special field name for _score to sort by score, and _doc to sort by index order.
 *
 * The order defaults to desc when sorting on the _score, and defaults to asc when sorting on anything else.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html
 */
abstract class AbstractSort
{
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';

    public const MODE_MIN    = 'min';
    public const MODE_MAX    = 'max';
    public const MODE_SUM    = 'sum';
    public const MODE_AVG    = 'avg';
    public const MODE_MEDIAN = 'median';

    /**
     * @var string Defaults to desc when sorting on the _score, and defaults to asc when sorting on anything else.
     */
    private $order;

    /**
     * @var string Elasticsearch supports sorting by array or multi-valued fields. The mode option controls what array
     * value is picked for sorting the document it belongs to.
     */
    private $mode;


    /**
     * @var array $options
     * @return array
     */
    protected function applyOptions(array $options)
    {
        $order = $this->getOrder();
        if (null !== $order) {
            $options['order'] = $order;
        }

        $mode = $this->getMode();
        if (null !== $mode) {
            $options['mode'] = $mode;
        }

        return $options;
    }


    /**
     * @param string $order
     * @return AbstractSort
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }


    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }


    /**
     * @param string $mode
     * @return AbstractSort
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }


    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }


    /**
     * @return mixed
     */
    abstract public function toArray();
}
