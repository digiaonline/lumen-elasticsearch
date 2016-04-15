<?php

class LeafQueryTest extends \Codeception\TestCase\Test
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Nord\Lumen\Elasticsearch\ElasticsearchService
     */
    protected $service;


    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->service = new \Nord\Lumen\Elasticsearch\ElasticsearchService(\Elasticsearch\ClientBuilder::fromConfig([]));
    }


    /**
     * Tests the Term Query.
     */
    public function testTermQuery()
    {
        $this->specify('term query was created', function () {
            $query = $this->service->createTermQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Queries\TermLevel\TermQuery');
        });

        $this->specify('term query format', function () {
            $query = $this->service->createTermQuery();
            $query
                ->setField('field')
                ->setValue('value');
            $array = $query->toArray();
            verify($array)->equals([
                'term' => ['field' => 'value']
            ]);
        });

        $this->specify('term query format with boost', function () {
            $query = $this->service->createTermQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setBoost(2.0);
            $array = $query->toArray();
            verify($array)->equals([
                'term' => [
                    'field' => [
                        'value' => 'value',
                        'boost' => 2.0
                    ]
                ]
            ]);
        });
    }


    /**
     * Tests the Range Query.
     */
    public function testRangeQuery()
    {
        $this->specify('range query was created', function () {
            $query = $this->service->createRangeQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Queries\TermLevel\RangeQuery');
        });

        $this->specify('range query format with gte', function () {
            $query = $this->service->createRangeQuery();
            $query
                ->setField('field')
                ->setGreaterThanOrEquals(10);
            $array = $query->toArray();
            verify($array)->equals([
                'range' => [
                    'field' => [
                        'gte' => 10
                    ]
                ]
            ]);
        });

        $this->specify('range query format with gt', function () {
            $query = $this->service->createRangeQuery();
            $query
                ->setField('field')
                ->setGreaterThan(10);
            $array = $query->toArray();
            verify($array)->equals([
                'range' => [
                    'field' => [
                        'gt' => 10
                    ]
                ]
            ]);
        });

        $this->specify('range query format with lte', function () {
            $query = $this->service->createRangeQuery();
            $query
                ->setField('field')
                ->setLessThanOrEquals(10);
            $array = $query->toArray();
            verify($array)->equals([
                'range' => [
                    'field' => [
                        'lte' => 10
                    ]
                ]
            ]);
        });

        $this->specify('range query format with lt', function () {
            $query = $this->service->createRangeQuery();
            $query
                ->setField('field')
                ->setLessThan(10);
            $array = $query->toArray();
            verify($array)->equals([
                'range' => [
                    'field' => [
                        'lt' => 10
                    ]
                ]
            ]);
        });

        $this->specify('range query format with boost', function () {
            $query = $this->service->createRangeQuery();
            $query
                ->setField('field')
                ->setBoost(2.0);
            $array = $query->toArray();
            verify($array)->equals([
                'range' => [
                    'field' => [
                        'boost' => 2.0
                    ]
                ]
            ]);
        });
    }


    /**
     * Tests the GeoDistance Query.
     */
    public function testGeoDistanceQuery()
    {
        $this->specify('geo distance query was created', function () {
            $query = $this->service->createGeoDistanceQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Queries\Geo\GeoDistanceQuery');
        });

        $this->specify('geo distance query format', function () {
            $query = $this->service->createGeoDistanceQuery();
            $query
                ->setLocation(60.169856, 24.938379)
                ->setDistance('10km')
                ->setField('field');
            $array = $query->toArray();
            verify($array)->equals([
                'geo_distance' => [
                    'distance' => '10km',
                    'field' => [
                        'lat' => 60.169856,
                        'lon' => 24.938379,
                    ]
                ]
            ]);
        });

        $this->specify('geo distance query format with distance type', function () {
            $query = $this->service->createGeoDistanceQuery();
            $query
                ->setLocation(60.169856, 24.938379)
                ->setDistance('10km')
                ->setDistanceType('arc')
                ->setField('field');
            $array = $query->toArray();
            verify($array)->equals([
                'geo_distance' => [
                    'distance' => '10km',
                    'distance_type' => 'arc',
                    'field' => [
                        'lat' => 60.169856,
                        'lon' => 24.938379,
                    ]
                ]
            ]);
        });
    }


    /**
     * Tests the Match Query.
     */
    public function testMatchQuery()
    {
        $this->specify('match query was created', function () {
            $query = $this->service->createMatchQuery();
            verify($query)->isInstanceOf('\Nord\Lumen\Elasticsearch\Queries\FullText\MatchQuery');
        });

        $this->specify('match query format', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value');
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => 'value'
                ]
            ]);
        });

        $this->specify('match query format with operator', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setOperator('and');
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'operator' => 'and'
                    ]
                ]
            ]);
        });

        $this->specify('match query format with zero_terms_query', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setZeroTermsQuery('none');
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'zero_terms_query' => 'none'
                    ]
                ]
            ]);
        });

        $this->specify('match query format with cutoff_frequency', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setCutOffFrequency(0.001);
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'cutoff_frequency' => 0.001
                    ]
                ]
            ]);
        });

        $this->specify('match query format with type', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setType('phrase');
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'type' => 'phrase'
                    ]
                ]
            ]);
        });

        $this->specify('match query format with type and slop', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setType('phrase')
                ->setSlop(0);
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'type' => 'phrase',
                        'slop' => 0
                    ]
                ]
            ]);
        });

        $this->specify('match query format with type and slop and max_expansions', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setType('phrase_prefix')
                ->setMaxExpansions(10);
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'type' => 'phrase_prefix',
                        'max_expansions' => 10
                    ]
                ]
            ]);
        });

        $this->specify('match query format with analyzer', function () {
            $query = $this->service->createMatchQuery();
            $query
                ->setField('field')
                ->setValue('value')
                ->setAnalyzer('custom_analyzer');
            $array = $query->toArray();
            verify($array)->equals([
                'match' => [
                    'field' => [
                        'query' => 'value',
                        'analyzer' => 'custom_analyzer'
                    ]
                ]
            ]);
        });
    }
}
