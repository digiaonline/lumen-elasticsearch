<?php namespace Nord\Lumen\Elasticsearch\Search\Sort;

class SortBuilder
{

    /**
     * @return FieldSort
     */
    public function createFieldSort()
    {
        return new FieldSort();
    }


    /**
     * @return ScoreSort
     */
    public function createScoreSort()
    {
        return new ScoreSort();
    }


    /**
     * @return DocSort
     */
    public function createDocSort()
    {
        return new DocSort();
    }

}
