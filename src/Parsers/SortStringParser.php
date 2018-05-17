<?php namespace Nord\Lumen\Elasticsearch\Parsers;

use Nord\Lumen\Elasticsearch\Search\Sort\AbstractSort;
use Nord\Lumen\Elasticsearch\Search\Sort\DocSort;
use Nord\Lumen\Elasticsearch\Search\Sort\FieldSort;
use Nord\Lumen\Elasticsearch\Search\Sort\ScoreSort;

/**
 * Example: "name:asc" => [['name' => ['order' => 'asc']]]
 */
class SortStringParser extends AbstractStringParser
{

    /**
     * @param string $string
     *
     * @return AbstractSort[]
     */
    public function buildSortFromString($string)
    {
        $sorts = [];

        if (!empty($string)) {
            foreach ($this->parse($string) as $item) {

                /*
                 * Position map:
                 * - 0 = field name
                 * - 1 = order (asc/desc)
                 * - 2 = mode (min/max/sum/avg/median)
                 */

                if (isset($item[0])) {
                    if ($item[0] === '_score') {
                        $sort = new ScoreSort();
                    } elseif ($item[0] === '_doc') {
                        $sort = new DocSort();
                    } else {
                        $sort = (new FieldSort())->setField($item[0]);
                    }

                    if (isset($item[1])) {
                        $sort->setOrder($item[1]);
                    }
                    if (isset($item[2])) {
                        $sort->setMode($item[2]);
                    }

                    $sorts[] = $sort;
                }
            }
        }

        return $sorts;
    }
}
