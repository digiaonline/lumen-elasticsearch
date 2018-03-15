<?php namespace Nord\Lumen\Elasticsearch\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Nested query allows to query nested objects / docs (see nested mapping).
 *
 * The query is executed against the nested objects / docs as if they were indexed as separate docs (they are,
 * internally) and resulting in the root parent doc (or parent nested mapping).
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html
 */
class NestedQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $path;

    /**
     * @inheritdoc
     * @throws InvalidArgument
     */
    public function toArray()
    {
        $query = $this->getQuery();

        if ($query === null) {
            throw new InvalidArgument('Query must be set');
        }
        
        $nested = [
            'path'  => $this->getPath(),
            'query' => $query->toArray(),
        ];

        $scoreMode = $this->getScoreMode();
        if (null !== $scoreMode) {
            $nested['score_mode'] = $scoreMode;
        }

        return ['nested' => $nested];
    }

    /**
     * @param string $path
     * @return NestedQuery
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
