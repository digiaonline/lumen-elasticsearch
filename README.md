# Lumen Elasticsearch

[![Build Status](https://travis-ci.org/digiaonline/lumen-elasticsearch.svg?branch=master)](https://travis-ci.org/digiaonline/lumen-elasticsearch)
[![Coverage Status](https://coveralls.io/repos/github/digiaonline/lumen-elasticsearch/badge.svg?branch=develop)](https://coveralls.io/github/digiaonline/lumen-elasticsearch?branch=develop)
[![Code Climate](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch/badges/gpa.svg)](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digiaonline/lumen-elasticsearch/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/digiaonline/lumen-elasticsearch/?branch=develop)
[![StyleCI](https://styleci.io/repos/46935324/shield?style=flat)](https://styleci.io/repos/46935324)
[![Latest Stable Version](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/version)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![Total Downloads](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/downloads)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Gitter](https://img.shields.io/gitter/room/norsoftware/open-source.svg?maxAge=2592000)](https://gitter.im/nordsoftware/open-source)

Simple wrapper of [Elasticsearch-PHP](https://github.com/elastic/elasticsearch-php) for the [Lumen PHP framework](http://lumen.laravel.com/).

## Version support

| Lumen  | Elasticsearch | Library |
|--------|---------------|---------|
| >= 5.4 | 5.x           | 3.x     |
| 5.4.x  | 5.x           | 2.x     |
| 5.4.x  | 2.x           | 1.x     |
| 5.2.x  | 2.x           | 0.7.x   |

## Requirements

- 3.x requires PHP 7.1 or newer
- 2.x requires PHP 5.6 or newer

## Usage

### Installation

Run the following command to install the package through Composer:

```sh
composer require nordsoftware/lumen-elasticsearch
```

Copy the configuration template in `config/elasticsearch.php` to your application's `config` directory and modify it 
to suit your needs.

Add the following line to ```bootstrap/app.php```:

```php
$app->register(Nord\Lumen\Elasticsearch\ElasticsearchServiceProvider::class);
```

You can now get the service instance using ```app(ElasticsearchServiceContract::class)``` or inject the ```ElasticsearchServiceContract``` where needed.

### Configuring your indices

You can create and delete indices using the provided console commands. Start by adding the commands to your console 
kernel:

```php
protected $commands = [
	...
	CreateCommand::class,
	DeleteCommand::class,
];
```

To create an index you need a configuration file that describes how the index should look. To create an index called 
`my-index`, create a file named `my-index.php` in the `config/elasticsearch` directory (create the directory if it 
doesn't exist) with the following contents:

```php
<?php

return [
    'index' => 'my-index',
    'body' => [
        'mappings' => [
            'my-model' => [
                'properties' => [
                    'id' => ['type' => 'string', 'index' => 'not_analyzed'],
                    'name' => ['type' => 'string'],
                ],
            ],
        ],
        'settings' => [
            'analysis' => [
                'filter' => [
                    'finnish_stop' => [
                        'type' => 'stop',
                        'stopwords' => '_finnish_',
                    ],
                    'finnish_stemmer' => [
                        'type' => 'stemmer',
                        'language' => 'finnish',
                    ],
                ],
                'analyzer' => [
                    'finnish' => [
                        'tokenizer' => 'standard',
                        'filter' => [
                            'lowercase',
                            'finnish_stop',
                            'finnish_stemmer',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
```

Please refer to the official Elasticsearch documentation for more information on how to define indices.

Now that you have a configuration file for your index, create it by running 
`php artisan elastic:index:create config/elasticsearch/my-index.php`.

To delete the index, run `php artisan elastic:index:delete my-index`.

### Indexing your data

To index data into your newly created indices you need to create a new console command that extends 
`Nord\Lumen\Elasticsearch\Console\IndexCommand`, then register that command in your console kernel. A sample 
implementation can look like this:

```php
<?php

use Nord\Lumen\Elasticsearch\Console\IndexCommand;

class IndexPersonsCommand extends IndexCommand
{

    protected $signature = 'app:index:persons';

    protected $description = 'Indexes all persons into the search index';

    public function getData()
    {
        return [
            new Person('Joe'),
            new Person('Jane'),
        ];
    }

    public function getIndex()
    {
        return 'persons';
    }

    public function getType()
    {
        return 'person';
    }

    public function getItemBody($item)
    {
        // Item is an instance of Person in this case
        return $item->getName();
    }

    public function getItemId($item)
    {
        // Item is an instance of Person in this case
        return $item->getId();
    }

    public function getItemParent($item)
    {
        // Return null if your objects don't have any parent/child relationship
        return $item->getParent();
    }

}
```

Now, run `php artisan app:index:persons` to index the data. You can now create additional commands for your other data 
types that need to be indexed.

#### Indexing single items

The console commands are useful when you want to index all items of a particular type, e.g. all persons in your 
database. However, if you update a single person you probably want to reindex just that person.

Here's an example:

```php
$service = app(ElasticsearchServiceContract::class);

$service->index([
	'index' => 'persons',
	'type'  => 'person',
	'id'    => $person->getId(),
	'body'  => $person->getName(),
]);
```

### Running queries

Queries against the search index are run by creating a query using the query builder, then creating a search using the 
query and finally executing the query using the provided service.

Here's an example:

```php
$service = app(ElasticsearchServiceContract::class);

// Create a query builder
$queryBuilder = $service->createQueryBuilder();

// Create the query
$query = $queryBuilder->createBoolQuery()
    ->addMust(
        $queryBuilder->createTermQuery()
            ->setField('user')
            ->setValue('kimchy'))
    ->addFilter(
        $queryBuilder->createTermQuery()
            ->setField('tag')
            ->setValue('tech'))
    ->addMustNot(
        $queryBuilder->createRangeQuery()
            ->setField('age')
            ->setGreaterThanOrEquals(18)
            ->setLessThanOrEquals(40))
    ->addShould(
        $queryBuilder->createTermQuery()
            ->setField('tag')
            ->setValue('wow'))
    ->addShould(
        $queryBuilder->createTermQuery()
            ->setField('tag')
            ->setValue('elasticsearch'));

// Create the search
$search = $service->createSearch()
    ->setIndex('index')
    ->setType('document')
    ->setQuery($query)
    ->setSize(50)
    ->setPage(1);

// Execute the search to retrieve the results
$result = $service->execute($search);
```

You can also perform raw queries:

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

## Creating and applying index migrations

Sometimes you need to make a change to an index mapping that requires data to be re-indexed. However, most of the 
time you don't need to actually index everything into Elasticsearch again, instead it is enough to just copy the data 
internally from your original index to a new one. This process can be performed seamlessly without downtime.

### Requirements

* You must add the `CreateMigrationCommand` and `ApplyMigrationCommand` commands to your console kernel
* Your Elasticsearch instance must support the `/_reindex` API. This is normally the case, however, Amazon 
Elasticsearch Service doesn't support it if you're using Elasticsearch 2.3 or older.

### Creating a migration

* Change your index definition (e.g. `config/search/your-index.php`) according to your needs
* Run `php artisan elastic:migrations:create config/search/your-index.php`)

This will create a directory named `versions` as well as a timestamped copy of your index definition file.

### Applying a migration

* Run `php artisan elastic:migrations:migrate config/search/your-index.php`

If you haven't run migrations before, your index will be replaced by an alias of the same name once the new index has 
been created. The next time you apply a migration, the alias will simply be updated.

If your documents are very large you may want to decrease the batch size used during re-indexing to prevent 
Elasticsearch from running out of memory. You can do so by passing `--batchSize=X` to the `elastic:migrations:migrate` 
command. If the option is omitted, the default value of 1000 is used.

## Contributing

Please read the [guidelines](.github/CONTRIBUTING.md).

## Running tests

Clone the project and install its dependencies by running:

```sh
composer install
```

Run the following command to run the test suite:

```sh
vendor/bin/phpunit
```

## License

See [LICENSE](LICENSE).
