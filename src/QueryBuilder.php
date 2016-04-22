<?php namespace Nord\Lumen\Elasticsearch;

class QueryBuilder
{

    /**
     * @return Queries\Compound\BoolQuery
     */
    public function createBoolQuery()
    {
        return new Queries\Compound\BoolQuery();
    }


    /**
     * @return Queries\FullText\MatchQuery
     */
    public function createMatchQuery()
    {
        return new Queries\FullText\MatchQuery();
    }


    /**
     * @return Queries\FullText\MultiMatchQuery
     */
    public function createMultiMatchQuery()
    {
        return new Queries\FullText\MultiMatchQuery();
    }


    /**
     * @return Queries\TermLevel\TermQuery
     */
    public function createTermQuery()
    {
        return new Queries\TermLevel\TermQuery();
    }


    /**
     * @return Queries\TermLevel\TermsQuery
     */
    public function createTermsQuery()
    {
        return new Queries\TermLevel\TermsQuery();
    }


    /**
     * @return Queries\TermLevel\RangeQuery
     */
    public function createRangeQuery()
    {
        return new Queries\TermLevel\RangeQuery();
    }


    /**
     * @return Queries\Geo\GeoDistanceQuery
     */
    public function createGeoDistanceQuery()
    {
        return new Queries\Geo\GeoDistanceQuery();
    }


    /**
     * @return Queries\Joining\NestedQuery
     */
    public function createNestedQuery()
    {
        return new Queries\Joining\NestedQuery();
    }


    /**
     * @return Queries\Joining\HasChildQuery
     */
    public function createHasChildQuery()
    {
        return new Queries\Joining\HasChildQuery();
    }


    /**
     * @return Queries\Joining\HasParentQuery
     */
    public function createHasParentQuery()
    {
        return new Queries\Joining\HasParentQuery();
    }
}
