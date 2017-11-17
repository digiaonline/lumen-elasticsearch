<?php

namespace Nord\Lumen\Elasticsearch\Console;

use Illuminate\Console\Command;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;
use Nord\Lumen\Elasticsearch\Helpers\ArrayHelper;
use Symfony\Component\Console\Helper\Table;

/**
 * Class AbstractCommand
 * @package Nord\Lumen\Elasticsearch\Console
 */
abstract class AbstractCommand extends Command
{

    /**
     * @var ElasticsearchServiceContract
     */
    protected $elasticsearchService;

    /**
     * AbstractMigrationCommand constructor.
     *
     * @param ElasticsearchServiceContract $elasticsearchService
     */
    public function __construct(ElasticsearchServiceContract $elasticsearchService)
    {
        parent::__construct();

        $this->elasticsearchService = $elasticsearchService;
    }
}
