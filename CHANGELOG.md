# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- GitHub Actions CI based on docker-compose-ci (MediaWiki 1.39 and 1.43)
- `requires` (MediaWiki >= 1.39, PHP >= 8.1) in `extension.json`
- PHPUnit integration test harness (`tests/phpunit/integration`) and migrated tests for `#complexarraydefine`, `#complexarrayprint`, `#complexarrayreset`, `#complexarrayunset`, `#complexarrayunique`, `#complexarraysize`, `#complexarraypush`, `#complexarraypusharray`, `#complexarrayaddvalue`, `#complexarraymerge`, `#complexarrayslice`, `#complexarraydiff`, `#complexarrayarraymap`, `#complexarrayextract`, `#complexarraymaptemplate`, `#complexarraymap`, `#complexarrayparent`, `#complexarraysearch`, `#complexarraysearcharray`, `#complexarraysort`, `#complexarraydefinedarrays`, the wildcard operator and `ComplexArrayWrapper`; overall line coverage is above 90 %

### Fixed
- Version check no longer passes a `Message` object to `Exception` (TypeError on PHP 8)
- Null passed to `explode()` in `#complexarrayprint` (deprecation on PHP 8.1+)
- `Message::toString()` called without format in error output (fatal on MediaWiki 1.43)
- Parser tests: add missing `!! end`/`!! Version 2` markers and rename duplicate test names
- `#complexarraymerge` with the `recursive` option now stores its result
- `#complexarraypush` with an empty value returns the "Value must not be omitted" error instead of raising a `TypeError`
- `#complexarraysort` with `keysort` no longer reuses the sort key of a previous call
- `ComplexArrayWrapper::reset()` no longer leaves properties unset, which raised an "Undefined property" warning on the next `get()`

### Changed
- Parser is no longer passed by reference in the function hook factories
- Dev dependencies (codesniffer, minus-x, parallel-lint) updated to versions installable on PHP 8.1+

### Removed
- Legacy parserTests files (`tests/parser/*.txt`) and their `run.php` runner; the PHPUnit suite is the single test reference
- Legacy GitLab CI configuration
