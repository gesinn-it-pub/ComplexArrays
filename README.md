Multidimensional and associative arrays for MediaWiki.

## Installation

```php
wfLoadExtension( 'ComplexArrays' );
```

Requires MediaWiki 1.39 or newer and PHP 8.1 or newer. Semantic MediaWiki is optional.

## Usage

Arrays are defined with `#complexarraydefine` and live for the duration of one parse of a page; they are not
shared between pages. Nested elements are addressed with square brackets, for example `colors[1]` or
`person[address][city]`. Every function also has short aliases (listed below).

```
{{#complexarraydefine:colors|red,green,blue}}
{{#complexarraydefine:person|(("name": "Ann", "tags": ["a", "b"]))}}
{{#complexarraypushvalue:colors|yellow}}
{{#complexarrayprint:colors}}
{{#complexarraysize:person[tags]}}
```

Arrays are written as a comma separated list, as JSON, or as JSON in which `{`/`}` are replaced by `((`/`))`
(so that the markup does not collide with template braces). `#complexarrayprint:name|markup` returns the latter.

If an argument is invalid, the function returns an error message instead of an empty result.

### Parser functions

| Function | Aliases | Arguments | Purpose |
| --- | --- | --- | --- |
| `#complexarraydefine` | `cadefine` | name, markup, separator, noparse | Define an array |
| `#complexarrayprint` | `caprint` | name, option (`markup`), parser behaviour (`noparse`, `nowiki`) | Print an array as a list or as markup |
| `#complexarraysize` | `casize` | name, option (`top` counts only the first level) | Number of elements |
| `#complexarrayaddvalue` | `caaddvalue`, `caadd`, `caaddv`, `caset` | name, value | Set a value at a key |
| `#complexarraypushvalue` | `complexarraypush`, `capush` | name, value, noparse | Append a value to an (sub)array |
| `#complexarraypusharray` | `capusharray` | new name, arrays... | Append arrays to another array |
| `#complexarrayunset` | `caunset`, `caremove` | name | Remove a key |
| `#complexarrayreset` | `careset` | name | Remove an array |
| `#complexarrayunique` | `caunique` | name | Remove duplicate values |
| `#complexarraysort` | `casort` | name, option, key | Sort (`sort`, `rsort`, `asort`, `arsort`, `krsort`, `natsort`, `natcasesort`, `shuffle`, `multisort`, `keysort`, `keysort,desc`) |
| `#complexarraysearch` | `casearch` | name, value | Find the key of a value |
| `#complexarraysearcharray` | `casearcharray`, `casearcha` | new name, name, value | Collect the sub-arrays that contain a value |
| `#complexarrayslice` | `caslice` | new name, name, offset, length | Create an array from part of another |
| `#complexarrayextract` | `caextract` | new name, key | Create an array from a sub-array |
| `#complexarraymerge` | `camerge` | new name, arrays..., options | Merge arrays |
| `#complexarraydiff` | `cadiff` | new name, arrays... | Difference of one-dimensional arrays |
| `#complexarrayparent` | `caparent`, `capapa`, `camama` | key | Parent of a key |
| `#complexarraymap` | `camap` | name, map key, map, separator, show | Apply a pattern to every element |
| `#complexarraymaptemplate` | `camaptemplate`, `camapt`, `catemplate` | name, template, options, delimiter | Call a template for every element |
| `#complexarrayarraymap` | `caamap`, `camapa` | list, delimiter, token, pattern, ... | Map a delimited list through a pattern |
| `#complexarraydefinedarrays` | `cadefinedarrays`, `cadefined`, `cad` | new name | Store the names of all defined arrays in an array |

## Configuration

Set the options in `LocalSettings.php` after loading the extension:

```php
wfLoadExtension( 'ComplexArrays' );

// Pre-define arrays (name => array) that are available to all parser functions.
$wgDefinedArraysGlobal = [ 'colors' => [ 'red', 'green' ] ];
```

The former global `$wfDefinedArraysGlobal` is deprecated. It is still honoured if
`$wgDefinedArraysGlobal` is not set, and a debug log entry (channel `ComplexArrays`) is written. It will
be removed in a future release.

## Semantic MediaWiki

If Semantic MediaWiki 5, 6 or 7 is installed, the result format `complexarray` is available without further
configuration. The extension does not create or change any files outside of its own directory.

### Upgrading

Earlier versions linked `ComplexArrayPrinter.php` into the directory of Semantic MediaWiki when
`$wgEnableResultPrinter` was set. This is no longer done, and the option is ignored. Remove the option from
`LocalSettings.php`; a link left behind in `extensions/SemanticMediaWiki/src/Query/ResultPrinters/` is not
used anymore and can be deleted by hand.

## Upgrading from WSArrays

ComplexArrays is the renamed WSArrays. Parser functions (including all aliases), the `ca-*` messages and the
`complexarray` result format are unchanged, so page content keeps working. In your setup:

- Load `ComplexArrays` instead of `WSArrays`: `wfLoadExtension( 'ComplexArrays' );`.
- The Composer package is now `gesinn-it/complex-arrays` (formerly `wikibase-solutions/w-s-arrays`).
- The debug log channel is now `ComplexArrays`.
- `$wgEnableResultPrinter` is gone; see [Semantic MediaWiki](#semantic-mediawiki).
- Pre-defined arrays are configured with `$wgDefinedArraysGlobal`; see [Configuration](#configuration).
- Custom code that used the removed classes `ExtensionFactory`, `Extension`, `ResultPrinterFactory` or the static
  `ComplexArrays::$arrays` must be adapted; classes now live in the `ComplexArrays\` namespace.

## License

ComplexArrays - Associative and multidimensional arrays for MediaWiki.
Copyright (C) 2019 Marijn van Wezel

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
