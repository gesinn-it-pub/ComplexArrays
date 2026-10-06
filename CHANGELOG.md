# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [6.0.0] - 2026-10-06

First release as ComplexArrays (formerly WSArrays). It requires MediaWiki 1.39+ and PHP 8.1+, fixes several state and parsing bugs, and adds CI, tests and documentation. See Breaking Changes before upgrading.

### Breaking Changes
- Rename the extension from WSArrays to ComplexArrays: extension name, composer package (`gesinn-it/complex-arrays`), main class, i18n file and debug log channel; parser functions, `ca-*` messages and the `complexarray` result format are unchanged [`f4e1f6f`](https://github.com/gesinn-it-pub/ComplexArrays/commit/f4e1f6f)
- Require MediaWiki 1.39 or newer and PHP 8.1 or newer [`dac0a72`](https://github.com/gesinn-it-pub/ComplexArrays/commit/dac0a72)
- Move all classes into the `ComplexArrays\` namespace with PSR-4 autoloading and remove the public static `ComplexArrays::$arrays` [`5678030`](https://github.com/gesinn-it-pub/ComplexArrays/commit/5678030) [`342d88f`](https://github.com/gesinn-it-pub/ComplexArrays/commit/342d88f)
- Remove the `$wgEnableResultPrinter` option: the `complexarray` result format is registered whenever Semantic MediaWiki is installed [`f2a83e7`](https://github.com/gesinn-it-pub/ComplexArrays/commit/f2a83e7)

### Added
- Add documentation: parser function overview, a worked example for every parser function, WSArrays upgrade notes and SMW result format description [`2ee28fb`](https://github.com/gesinn-it-pub/ComplexArrays/commit/2ee28fb) [`ff664db`](https://github.com/gesinn-it-pub/ComplexArrays/commit/ff664db) [`dd51dad`](https://github.com/gesinn-it-pub/ComplexArrays/commit/dd51dad)
- Support the `complexarray` result format with Semantic MediaWiki 5, 6 and 7 without symlinking a file into SMW [`f2a83e7`](https://github.com/gesinn-it-pub/ComplexArrays/commit/f2a83e7)

### Changed
- Use the unmodified GNU GPL v2 text as `LICENSE` and name gesinn.it as author (the license stays GPL-2.0-or-later) [`1bfed18`](https://github.com/gesinn-it-pub/ComplexArrays/commit/1bfed18)
- `#complexarrayslice` treats offset and length as integers; an omitted length slices to the end [`6c9b309`](https://github.com/gesinn-it-pub/ComplexArrays/commit/6c9b309)
- Read `$wgDefinedArraysGlobal` through MediaWiki's configuration; the legacy `$wfEnableResultPrinter` and `$wfDefinedArraysGlobal` globals remain as a deprecated fallback [`33f6791`](https://github.com/gesinn-it-pub/ComplexArrays/commit/33f6791)

### Removed
- Remove the unused `ComplexArrayWrapper` class [`11c0957`](https://github.com/gesinn-it-pub/ComplexArrays/commit/11c0957)

### Fixed
- Fix `#complexarraymap` failing with "Unknown modifier" for mapping keys containing `/` [`1d5f033`](https://github.com/gesinn-it-pub/ComplexArrays/commit/1d5f033)
- Fix JSON and `((`/`))` markup conversion misreading braces and doubled parentheses inside keys and values [`b92afce`](https://github.com/gesinn-it-pub/ComplexArrays/commit/b92afce)
- Fix the Semantic MediaWiki result format defining its array outside the page's parser and repeating rows of earlier queries [`f2a83e7`](https://github.com/gesinn-it-pub/ComplexArrays/commit/f2a83e7)
- Fix defined arrays leaking between pages, previews, jobs and API parses, and parser function state surviving into the next call [`342d88f`](https://github.com/gesinn-it-pub/ComplexArrays/commit/342d88f)
- Fix arguments with the value `0` being treated as omitted [`6a051aa`](https://github.com/gesinn-it-pub/ComplexArrays/commit/6a051aa)
- Fix `#complexarraydefine` with an empty JSON list (`[]`) silently defining nothing [`e418744`](https://github.com/gesinn-it-pub/ComplexArrays/commit/e418744)
- Fix `#complexarraymerge` with `recursive` not storing its result [`6ce540c`](https://github.com/gesinn-it-pub/ComplexArrays/commit/6ce540c)
- Fix `#complexarraypush` with an empty value raising a `TypeError` instead of the "Value must not be omitted" error [`6ce540c`](https://github.com/gesinn-it-pub/ComplexArrays/commit/6ce540c)
- Fix `#complexarraysort` with `keysort` reusing the sort key of a previous call [`6c9b309`](https://github.com/gesinn-it-pub/ComplexArrays/commit/6c9b309)
- Fix fatal errors and deprecations on PHP 8.1+ and MediaWiki 1.43 in error output and `#complexarrayprint` [`dac0a72`](https://github.com/gesinn-it-pub/ComplexArrays/commit/dac0a72)

[Unreleased]: https://github.com/gesinn-it-pub/ComplexArrays/compare/6.0.0...HEAD
[6.0.0]: https://github.com/gesinn-it-pub/ComplexArrays/compare/v5.5.5...6.0.0
