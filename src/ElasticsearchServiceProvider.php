<?php namespace Nord\Lumen\Elasticsearch;

use Elasticsearch\Client;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Nord\Lumen\Elasticsearch\Facades\ElasticsearchService;

/**
 * Service provider that binds the `\Elasticsearch\Client` to the application container.
 * Use the client through the `ElasticsearchService` facade.
 *
 * In your application bootstrap file, register the service provider:
 * `$app->register('Nord\Lumen\Elasticsearch\ElasticsearchServiceProvider');`
 *
 * @see Nord\Lumen\Elasticsearch\Facades\ElasticsearchService::getFacadeAccessor
 */
class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerBindings($this->app);
    }

    /**
     * Registers container bindings.
     *
     * @param Container $container
     */
    protected function registerBindings(Container $container)
    {
        $container->singleton(ElasticsearchService::class, function ($container) {
            return $this->createService($container);
        });
    }

    /**
     * Creates the service instance.
     * The default configuration can be overridden from your application by registering an `elasticsearch` config,
     * i.e. `config/elasticsearch.php`.
     *
     * @param Container $container
     *
     * @return Client
     */
    protected function createService(Container $container)
    {
        $path = storage_path();
        $sapi = php_sapi_name();
        $params = [
            'hosts'      => ['localhost:9200'],
            'logging'    => true,
            'logPath'    => "{$path}/logs/elasticsearch-{$sapi}.log",
            'logLevel'   => Logger::INFO,
            'tracePath'  => "{$path}/logs/elasticsearch-trace-{$sapi}.log",
            'traceLevel' => Logger::INFO,
        ];

        if (!empty($container['config']['elasticsearch']) && is_array($container['config']['elasticsearch'])) {
            $params = array_merge($params, $container['config']['elasticsearch']);
        }

        return new Client($params);
    }
}
