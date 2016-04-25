# Lumen Elasticsearch

[![Build Status](https://travis-ci.org/nordsoftware/lumen-elasticsearch.svg?branch=master)](https://travis-ci.org/nordsoftware/lumen-elasticsearch)
[![Coverage Status](https://coveralls.io/repos/github/nordsoftware/lumen-elasticsearch/badge.svg?branch=master)](https://coveralls.io/github/nordsoftware/lumen-elasticsearch?branch=master)
[![Code Climate](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch/badges/gpa.svg)](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch)
[![Latest Stable Version](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/version)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![Total Downloads](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/downloads)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Gitter](https://img.shields.io/gitter/room/norsoftware/chat.svg?maxAge=2592000)](https://gitter.im/nordsoftware/chat)

Simple wrapper of [Elasticsearch-PHP](https://github.com/elastic/elasticsearch-php) for the [Lumen PHP framework](http://lumen.laravel.com/).

## Requirements

- PHP 5.5 or newer
- [Composer](http://getcomposer.org)

## Usage

### Installation

Run the following command to install the package through Composer:

```sh
composer require nordsoftware/lumen-elasticsearch
```

### Bootstrapping

Add the following line to ```bootstrap/app.php```:

```php
$app->register(Nord\Lumen\Elasticsearch\ElasticsearchServiceProvider::class);
```

You can now get the service instance using ```app(ElasticsearchServiceContract::class)``` or inject the ```ElasticsearchServiceContract``` where needed.

### Configure

Copy the configuration template in `config/elasticsearch.php` to your application's `config` directory and modify.
For more information see the [Configuration Files](http://lumen.laravel.com/docs/configuration#configuration-files)
section in the Lumen documentation.

### Quickstart

[Bool Query](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html)

Using the query builder:

```php
$service = app(ElasticsearchServiceContract::class);

$query = $service->createBoolQuery();
$query->addMust($service->createTermQuery()->setField('user')->setValue('kimchy'));
$query->addFilter($service->createTermQuery()->setField('tag')->setValue('tech'));
$query->addMustNot($service->createRangeQuery()->setField('age')->setGreaterThanOrEquals(10)->setLessThanOrEquals(20));
$query->addShould($service->createTermQuery()->setField('tag')->setValue('wow'));
$query->addShould($service->createTermQuery()->setField('tag')->setValue('elasticsearch'));
$query->setSize(50)->setPage(1);

$result = $service->changeIndex('index')->changeType('document')->execute($query);
```

Raw arrays:

```php
$service = app(ElasticsearchServiceContract::class);

$result = $service->search([
    'index' => 'index',
    'type'  => 'document',
    'body'  => [
        'query' => [
            'bool' => [
                'must' => [
                    'term' => ['user' => 'kimchy']
                ],
                'filter' => [
                    'term' => ['tag' => 'tech']
                ],
                'must_not' => [
                    'range' => [
                        'age' => ['gte' => 10, 'lte' => 20]
                    ]
                ],
                'should' => [
                    [
                        'term' => ['tag' => 'wow']
                    ],
                    [
                        'term' => ['tag' => 'elasticsearch']
                    ]
                ],
            ]
        ],
        'size' => 50,
        'from' => 0
    ],
]);
```

## Contributing

Please read the [guidelines](.github/CONTRIBUTING.md).

## Running tests

Clone the project and install its dependencies by running:

```sh
composer install
```

Run the following command to run the test suite:

```sh
vendor/bin/codecept run unit
```

## License

See [LICENSE](LICENSE).
