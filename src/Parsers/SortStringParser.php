<?php namespace Nord\Lumen\Elasticsearch\Parsers;

use Nord\Lumen\Elasticsearch\Search\Sort\AbstractSort;
use Nord\Lumen\Elasticsearch\Search\Sort\SortBuilder;

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
            $sortBuilder = new SortBuilder();
            foreach ($this->parse($string) as $item) {

                /*
                 * Position map:
                 * - 0 = field name
                 * - 1 = order (asc/desc)
                 * - 2 = mode (min/max/sum/avg/median)
                 */

                if (!isset($item[0])) {
                    continue;
                }

                if ($item[0] === '_score') {
                    $sort = $sortBuilder->createScoreSort();
                } elseif ($item[0] === '_doc') {
                    $sort = $sortBuilder->createDocSort();
                } else {
                    $sort = $sortBuilder->createFieldSort()->setField($item[0]);
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

        return $sorts;
    }

}
