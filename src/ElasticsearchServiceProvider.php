<?php namespace Nord\Lumen\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Nord\Lumen\Elasticsearch\Contracts\ElasticsearchServiceContract;

class ElasticsearchServiceProvider extends ServiceProvider
{
    protected const CONFIG_KEY = 'elasticsearch';

    /**
     * @inheritdoc
     */
    public function register()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->app->/** @scrutinizer ignore-call */ configure(self::CONFIG_KEY);

        $this->registerBindings();
    }

    /**
     * Register bindings.
     */
    protected function registerBindings()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $config = $this->app['config']->get('elasticsearch', []);
        
        // Extract the index_prefix parameter if present
        $indexPrefix = $config['index_prefix'];
        unset($config['index_prefix']);

        $this->app->/** @scrutinizer ignore-call */ singleton(ElasticsearchServiceContract::class,
            static function () use ($config, $indexPrefix) {
                return new ElasticsearchService(ClientBuilder::fromConfig($config), $indexPrefix);
            });
    }
}
