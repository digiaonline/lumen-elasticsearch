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

        $this->app->/** @scrutinizer ignore-call */ singleton(ElasticsearchServiceContract::class, function () use ($config) {
            return new ElasticsearchService(ClientBuilder::fromConfig($config));
        });
    }
}
