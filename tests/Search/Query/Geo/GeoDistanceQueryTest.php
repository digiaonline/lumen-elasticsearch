<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Geo;

use Nord\Lumen\Elasticsearch\Search\Query\Geo\GeoDistanceQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class GeoDistanceQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Geo
 */
class GeoDistanceQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new GeoDistanceQuery();
        $query->setLocation(60.169856, 24.938379)
              ->setDistance('10km')
              ->setField('field');

        $this->assertEquals([
            'geo_distance' => [
                'distance' => '10km',
                'field'    => [
                    'lat' => 60.169856,
                    'lon' => 24.938379,
                ],
            ],
        ], $query->toArray());

        $query = new GeoDistanceQuery();
        $query->setLocation(60.169856, 24.938379)
              ->setDistance('10km')
              ->setDistanceType('arc')
              ->setField('field');

        $this->assertEquals([
            'geo_distance' => [
                'distance'      => '10km',
                'distance_type' => 'arc',
                'field'         => [
                    'lat' => 60.169856,
                    'lon' => 24.938379,
                ],
            ],
        ], $query->toArray());
    }
}
