<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Geo;

use Nord\Lumen\Elasticsearch\Search\Traits\HasField;

/**
 * Filters documents that include only hits that exists within a specific distance from a geo point.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html
 */
class GeoDistanceQuery extends AbstractQuery
{
    use HasField;
    
    const DISTANCE_TYPE_SLOPPY_ARC = 'sloppy_arc';
    const DISTANCE_TYPE_ARC = 'arc';
    const DISTANCE_TYPE_PLANE = 'plane';

    /**
     * @var mixed
     */
    private $latitude;

    /**
     * @var mixed
     */
    private $longitude;

    /**
     * @var string The radius of the circle centred on the specified location. Points which fall into this circle are
     * considered to be matches. The distance can be specified in various units.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#distance-units
     */
    private $distance;

    /**
     * @var string How to compute the distance. Can either be sloppy_arc (default), arc (slightly more precise but
     * significantly slower) or plane (faster, but inaccurate on long distances and close to the poles).
     */
    private $distanceType;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $geoDistance = [
            'distance' => $this->getDistance(),
            $this->getField() => [
                'lat' => $this->getLatitude(),
                'lon' => $this->getLongitude(),
            ]
        ];

        $distanceType = $this->getDistanceType();
        if (!is_null($distanceType)) {
            $geoDistance['distance_type'] = $distanceType;
        }

        return ['geo_distance' => $geoDistance];
    }


    /**
     * @param mixed $latitude
     * @param mixed $longitude
     * @return GeoDistanceQuery
     */
    public function setLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }


    /**
     * @param string $distance
     * @return GeoDistanceQuery
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }


    /**
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }


    /**
     * @param string $distanceType
     * @return GeoDistanceQuery
     */
    public function setDistanceType($distanceType)
    {
        $this->distanceType = $distanceType;
        return $this;
    }


    /**
     * @return string
     */
    public function getDistanceType()
    {
        return $this->distanceType;
    }
}
