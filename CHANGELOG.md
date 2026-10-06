# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- Tests for the SMW result format (registration and JSON scripts run with SMW's test runner), executed only where Semantic MediaWiki is installed; CI installs SMW 7.3.0 and SRF for the coverage leg and SMW 6.0.0 for an additional leg
- GitHub Actions CI based on docker-compose-ci (MediaWiki 1.39 and 1.43)
- Phan static analysis (`composer phan`, `make composer-phan`); any finding fails CI
- `requires` (MediaWiki >= 1.39, PHP >= 8.1) in `extension.json`
- PHPUnit integration test harness (`tests/phpunit/integration`) and migrated tests for `#complexarraydefine`, `#complexarrayprint`, `#complexarrayreset`, `#complexarrayunset`, `#complexarrayunique`, `#complexarraysize`, `#complexarraypush`, `#complexarraypusharray`, `#complexarrayaddvalue`, `#complexarraymerge`, `#complexarrayslice`, `#complexarraydiff`, `#complexarrayarraymap`, `#complexarrayextract`, `#complexarraymaptemplate`, `#complexarraymap`, `#complexarrayparent`, `#complexarraysearch`, `#complexarraysearcharray`, `#complexarraysort`, `#complexarraydefinedarrays` and the wildcard operator; overall line coverage is above 90 %

### Changed
- `LICENSE` is now the unmodified GNU GPL v2 text (the license stays GPL-2.0-or-later); gesinn.it GmbH & Co. KG (Alexander Gesinn) is named as author in `extension.json`, `composer.json`, the README and the file headers
- Modernised extension registration: manifest version 2, PSR-4 autoloading (`AutoloadNamespaces`) with all classes moved into the `ComplexArrays\` namespace (parser functions in `ComplexArrays\ParserFunctions`, files renamed accordingly), and a `ComplexArrays\Hooks` handler class for `ParserFirstCallInit` that registers the parser functions from an explicit list instead of globbing `src/classes`
- Upgraded MediaWiki CodeSniffer to 48.0.2 (the newest release supporting PHP 8.1), removed all PHPCS rule exclusions except the integration-test `@covers` one and fixed the resulting findings: complete docblocks, no error suppression operator, line length, and lower camel case names (`wsonToJson`, `jsonToWson`, `formatPropertyOfType*`); the internal global `$wfDefinedArraysGlobal` is now `$wgComplexArraysDefinedArrays`
- Renamed the extension from WSArrays to ComplexArrays (extension name, main class `ComplexArrays`, `ComplexArrays.i18n.php`, debug log channel, composer package `gesinn-it/complex-arrays`); parser functions, `ca-*` messages and the `complexarray` result format are unchanged
- Docblocks: import `Exception` where `@throws Exception` is documented, drop the stale `@extends ComplexArrays` annotations and correct parameter, return and property types
- `GlobalFunctions::error()` takes a message key and parameters instead of a `Message` object
- `#complexarrayslice` casts offset and length to integers; an omitted length slices to the end, `0` yields an empty slice
- Parser is no longer passed by reference in the function hook factories
- Dev dependencies (codesniffer, minus-x, parallel-lint) updated to versions installable on PHP 8.1+

### Removed
- The unused class `ComplexArrayWrapper` and its tests
- The `$wgEnableResultPrinter` option: the `complexarray` result format is registered whenever Semantic MediaWiki is installed
- Stale `VERSION` constant of the main class
- `ExtensionFactory`, `Extension` and `ResultPrinterFactory` (including their `require_once`/`spl_autoload_register` loading), the obsolete MediaWiki/PHP version checks and the `SkipVersionControl` option
- Legacy parserTests files (`tests/parser/*.txt`) and their `run.php` runner; the PHPUnit suite is the single test reference
- Legacy GitLab CI configuration

### Fixed
- `#complexarraymap` with a mapping key containing `/` no longer fails with "Unknown modifier": the key is now quoted for use as part of a regular expression including its delimiter
- Converting between JSON and the `((`/`))` markup no longer relies on regular expressions guessing what is inside of a string: braces and doubled parentheses in keys and values are left alone (a key such as `{k}` made the markup unrecognisable)
- The `complexarray` result format of Semantic MediaWiki no longer symlinks a file into SMW's directory: `ComplexArrays\SMW\ComplexArrayPrinter` is autoloaded from this extension and registered through the `SMW::Setup::AfterInitializationComplete` hook; it defines the array in the parser that runs the query (it was written to an obsolete global and never visible to the page), and no longer repeats rows of earlier queries. It works with Semantic MediaWiki 5, 6 and 7
- Defined arrays no longer leak between pages, previews, jobs and API parses: they are kept per parser in `ComplexArrays\ArrayStore` and cleared on `ParserClearState` instead of in the public static `ComplexArrays::$arrays` (removed); the per-call state of the parser functions (for example the separator and show flag of `#complexarraymap`) is no longer kept in static properties, so it cannot survive into the next call
- The options `$wgEnableResultPrinter` and `$wgDefinedArraysGlobal` declared in `extension.json` are now read through MediaWiki's configuration; the legacy globals `$wfEnableResultPrinter` and `$wfDefinedArraysGlobal` remain as a deprecated fallback with a debug log entry, and the options are documented in the README
- Parser function arguments with the value `0` are no longer treated as omitted (for example `{{#complexarraypush:list|0}}` was rejected with "Value must not be omitted")
- Malformed `use` statements in `ComplexArrays\Hooks` (missing namespace separator) that pointed the parser function class imports at non-existent classes
- Version check no longer passes a `Message` object to `Exception` (TypeError on PHP 8)
- `#complexarraydefine` with an empty JSON list (`[]`) now defines an empty array instead of silently defining nothing behind a discarded "markup is not recognized" error; that error is now returned instead of being thrown away
- Null passed to `explode()` in `#complexarrayprint` (deprecation on PHP 8.1+)
- `Message::toString()` called without format in error output (fatal on MediaWiki 1.43)
- Parser tests: add missing `!! end`/`!! Version 2` markers and rename duplicate test names
- `#complexarraymerge` with the `recursive` option now stores its result
- `#complexarraypush` with an empty value returns the "Value must not be omitted" error instead of raising a `TypeError`
- `#complexarraysort` with `keysort` no longer reuses the sort key of a previous call
