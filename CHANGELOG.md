# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- GitHub Actions CI based on docker-compose-ci (MediaWiki 1.39 and 1.43)
- `requires` (MediaWiki >= 1.39, PHP >= 8.1) in `extension.json`
- PHPUnit integration test harness (`tests/phpunit/integration`) and migrated tests for `#complexarraydefine`, `#complexarrayprint`, `#complexarrayreset`, `#complexarrayunset`, `#complexarrayunique`, `#complexarraysize`, `#complexarraypush`, `#complexarraypusharray`, `#complexarrayaddvalue`, `#complexarraymerge`, `#complexarrayslice` and `#complexarraydiff`

### Fixed
- Version check no longer passes a `Message` object to `Exception` (TypeError on PHP 8)
- Null passed to `explode()` in `#complexarrayprint` (deprecation on PHP 8.1+)
- `Message::toString()` called without format in error output (fatal on MediaWiki 1.43)
- Parser tests: add missing `!! end`/`!! Version 2` markers and rename duplicate test names

### Changed
- Parser is no longer passed by reference in the function hook factories
- Dev dependencies (codesniffer, minus-x, parallel-lint) updated to versions installable on PHP 8.1+

### Known issues
Parser test baseline (`tests/parser/*.txt`, identical on MW 1.39/PHP 8.1 and MW 1.43/PHP 8.3);
remaining failures are pre-existing expectation drift, not load errors:
- Passing: Parent 3/3, Search 5/5, MapTemplate 5/5, Map 7/7
- Failing: ArrayMap 7/11, Extract 3/4, SearchArray 1/3, Sort 1/14, Wildcard 1/3
- Causes: trailing blank line in expected HTML (current parser output has none), `mw-empty-elt`
  class on empty list items, and a `, ` separator in `#complexarrayarraymap` output

Bugs found while migrating (tests intentionally omitted until fixed):
- `#complexarraymerge` with the `recursive` option never stores the result (inverted `is_array` check), so the new array stays undefined
- `#complexarraypush` with an empty value (`{{#complexarraypush:name|}}`) raises a `TypeError` instead of the "Value must not be omitted" error

### Removed
- Legacy GitLab CI configuration
