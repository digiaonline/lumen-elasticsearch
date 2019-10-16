<?php

namespace Nord\Lumen\Elasticsearch\Pipelines\Stages;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use League\Pipeline\StageInterface;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\IndexNamePrefixer;
use Nord\Lumen\Elasticsearch\Pipelines\Payloads\ApplyMigrationPayload;

/**
 * Class UpdateAliasStage
 * @package Nord\Lumen\Elasticsearch\Pipelines\Stages
 */
class UpdateIndexAliasStage implements StageInterface
{

    /**
     * @var ElasticsearchServiceContract
     */
    private $elasticsearchService;

    /**
     * CheckIndexExistsStage constructor.
     *
     * @param ElasticsearchServiceContract $elasticsearchService
     */
    public function __construct(ElasticsearchServiceContract $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    /**
     * @inheritDoc
     */
    public function __invoke($payload)
    {
        /** @var ApplyMigrationPayload $payload */

        $indices         = $this->elasticsearchService->indices();
        $alias           = $payload->getPrefixedIndexName();
        $orphanedIndices = [];

        // If we already have an alias in place we store the indices it points to right now
        try {
            $aliasDefinition = $indices->getAlias([
                'name' => $alias,
            ]);

            $orphanedIndices = array_keys($aliasDefinition);
        } catch (Missing404Exception $e) {
            // If the alias doesn't exist we need to remove the index that has its name
            if ($indices->exists(['index' => $alias])) {
                $indices->delete(['index' => $alias]);
            }
        }

        // Revert temporary index settings
        $indices->putSettings([
            'index' => $payload->getPrefixedTargetVersionName(),
            'body'  => [
                'refresh_interval'   => '1s',
                'number_of_replicas' => $payload->getNumberOfReplicas(),
            ],
        ]);

        // Update the alias definition
        $indices->updateAliases([
            'body' => [
                'actions' => [
                    [
                        'add' => [
                            'index' => $payload->getPrefixedTargetVersionName(),
                            'alias' => $alias,
                        ],
                    ],
                ],
            ],
        ]);

        // Remove orphaned indices
        foreach ($orphanedIndices as $orphanedIndex) {
            $prefixedOrphanedIndex = IndexNamePrefixer::getPrefixedIndexName($orphanedIndex);
            
            $indices->delete(['index' => $prefixedOrphanedIndex]);
        }

        return $payload;
    }
}
