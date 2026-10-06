# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- GitHub Actions CI based on docker-compose-ci (MediaWiki 1.39 and 1.43)
- `requires` (MediaWiki >= 1.39, PHP >= 8.1) in `extension.json`

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
- Passing: Reset 3/3, Parent 3/3, Search 5/5, Size 5/5, MapTemplate 5/5, Map 7/7
- Failing: AddValue 1/5, ArrayMap 7/11, DefinePrint 7/17, Diff 2/3, Extract 3/4, Merge 1/6,
  Push 0/9, PushArray 2/5, SearchArray 1/3, Slice 6/7, Sort 1/14, Unique 1/5, Unset 2/5, Wildcard 1/3
- Causes: trailing blank line in expected HTML (current parser output has none), `mw-empty-elt`
  class on empty list items, and a `, ` separator in `#complexarrayarraymap` output

### Removed
- Legacy GitLab CI configuration
