# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

NOTE: Always keep an Unreleased version at the top of this CHANGELOG for easy updating.

## [Unreleased] - YYYY-MM-DD
### Added
- For new features.

### Changed
- For changes in existing functionality.

### Deprecated
- For once-stable features removed in upcoming releases.

### Removed
- For deprecated features removed in this release.

### Fixed
- For any bug fixes.

### Security
- To invite users to upgrade in case of vulnerabilities.

## [2.0.0] - 2017-10-03

- Initial support for Elasticsearch 5.x 

## [1.3.0] - 2017-09-13
### Added
- Support for wildcard and regexp queries
- Better documentation in the README
- Support re-indexing
- Support update by query

### Fixed
- Fixed some badges in the README
- Reduced some code duplication by using traits

## [1.2.0] - 2017-06-21
### Added
- Function for getting the total count.

## [1.1.2] - 2017-06-16
### Fixed
- Invert the logic to determine the correct "from" value.

## [1.1.1] - 2017-06-16
### Added
- PHP 7.1 to Travis CI.

## [1.1.0] - 2017-06-16
### Added
- Ability to specify "from" directly, bypassing the "page" abstraction

## [1.0.1] - 2017-02-09
### Changed
- Composer.lock.

## [1.0.0] - 2017-02-09
### Added
- CHANGELOG.md

### Changed
- Update composer to use lumen-framework 5.4.

### Removed
- Function call to undefined method setSettings() in ElasticsearchService.

## [0.7.0] - 2017-02-09
### Added
- Support for function_score queries.
- Scrutinizer badge.

## [0.6.0] - 2016-06-10
### Added
- StyleCI Badge.
- Support for aggregations.

### Changed
- Gitter badge link.

## [0.5.0] - 2016-04-29
### Added
- Sorting support.
- Query sort string parser.
- Unit tests for sort string parser.

### Removed
- Unused property from ElasticsearchService.

## [0.4.0] - 2016-04-25
### Added
- Unit tests for joining queries, bulk query and bulk action.
- Gitter badge.

### Changed
- Update README.
- Search API related queries under new namespace.
- License badge.
- Expose bulk() method through service contract.

### Fixed
- Tests to work with new query builder.
- MultiMatch query options.

## [0.3.1] - 2016-04-20
### Fixed
- Pagination query to accept real page number instead of one less.
- Pagerfanta page calculation.
- Pagerfanta tests.
- README.

## [0.3.0] - 2016-04-20
### Added
- Pagerfanta to support pagination.
- Tests for Pagerfanta.

### Changed
- Update README.

## [0.2.0] - 2016-04-15
### Added
- Badges.
- composer.lock.
- Unit tests.
- Code coverage.
- Travis CI.
- Term query.
- Support for basic query building.

### Changed
- Update documentation.
- Cleaned up default config.

## [0.1.0] - 2015-11-26 
### Added
- Project files.
- Service provider that exposes a configurable elasticsearch client.

[Unreleased]: https://github.com/nordsoftware/lumen-elasticsearch/compare/1.2.0...HEAD
[1.2.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/1.1.2...1.2.0
[1.1.2]: https://github.com/nordsoftware/lumen-elasticsearch/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/nordsoftware/lumen-elasticsearch/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.7.0...1.0.0
[0.7.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.6.0...0.7.0
[0.6.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.5.0...0.6.0
[0.5.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.3.1...0.4.0
[0.3.1]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/nordsoftware/lumen-elasticsearch/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/nordsoftware/lumen-elasticsearch/tree/0.1.0
