<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Geo;

use Nord\Lumen\Elasticsearch\Search\Query\QueryDSL;

/**
 * Elasticsearch supports two types of geo data: geo_point fields which support lat/lon pairs, and geo_shape fields,
 * which support points, lines, circles, polygons, multi-polygons etc.
 *
 * The queries in this group are:
 *
 * - "geo_shape" query
 * Find document with geo-shapes which either intersect, are contained by, or do not intersect with the specified
 * geo-shape.
 *
 * - "geo_bounding_box" query
 * Finds documents with geo-points that fall into the specified rectangle.
 *
 * - "geo_distance" query
 * Finds document with geo-points within the specified distance of a central point.
 *
 * - "geo_distance_range" query
 * Like the geo_point query, but the range starts at a specified distance from the central point.
 *
 * - "geo_polygon" query
 * Find documents with geo-points within the specified polygon.
 *
 * - "geohash_cell" query
 * Find geo-points whose geohash intersects with the geohash of the specified point.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/geo-queries.html
 */
abstract class AbstractQuery extends QueryDSL
{

}
