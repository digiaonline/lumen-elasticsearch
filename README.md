# Lumen Elasticsearch

[![Code Climate](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch/badges/gpa.svg)](https://codeclimate.com/github/nordsoftware/lumen-elasticsearch)
[![Latest Stable Version](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/version)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![Total Downloads](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/downloads)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)
[![License](https://poser.pugx.org/nordsoftware/lumen-elasticsearch/license)](https://packagist.org/packages/nordsoftware/lumen-elasticsearch)

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
$app->register('Nord\Lumen\Elasticsearch\ElasticsearchServiceProvider::class');
```

You can now get the service instance using ```app(ElasticsearchServiceContract::class)``` or inject the ```ElasticsearchServiceContract``` where needed.

### Configure

Copy the configuration template in `config/elasticsearch.php` to your application's `config` directory and modify.
For more information see the [Configuration Files](http://lumen.laravel.com/docs/configuration#configuration-files)
section in the Lumen documentation.

## Contributing

Please read the [guidelines](.github/CONTRIBUTING.md).

## License

See [LICENSE](LICENSE).
