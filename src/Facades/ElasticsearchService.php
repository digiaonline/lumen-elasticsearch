<?php namespace Nord\Lumen\Elasticsearch\Facades;

use Elasticsearch\Namespaces\IndicesNamespace;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for the elasticsearch client which is registered as a shared binding to the application container
 * in the service provider.
 *
 * In your application bootstrap file, register facades:
 * `$app->withFacades();`
 *
 * Usage:
 * All public methods in `\Elasticsearch\Client` can be used through this facade, e.g. ElasticsearchService::index().
 *
 * @see Nord\Lumen\Elasticsearch\ElasticsearchServiceProvider::registerBindings
 *
 * @method static array search(array $params = [])
 * @method static array index(array $params = [])
 * @method static array delete(array $params = [])
 * @method static array create(array $params = [])
 * @method static array exists(array $params = [])
 * @method static IndicesNamespace indices()
 */
class ElasticsearchService extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return ElasticsearchService::class;
    }
}
