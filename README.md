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

### Worked example

The following steps build on each other, as on a single wiki page: a small team directory with the skills of
the team. Results are shown as rendered by the parser; lists are printed as bullet lists. Functions that only
change arrays (define, push, unset, sort, ...) print nothing.

**1. Define arrays** with `#complexarraydefine`. The markup can be a comma separated list, JSON, or JSON using
`((`/`))` for objects. A third argument sets another separator for plain lists (`a;b;c|;`).

```
{{#complexarraydefine:team|[
  (("name": "Ann", "role": "dev")),
  (("name": "Bob", "role": "ops")),
  (("name": "Cy", "role": "dev"))
]}}
{{#complexarraydefine:skills|php,sql,php,docker}}
```

**2. Read and print** with `#complexarrayprint`: a single element by its path, a whole array as list, or as markup.

```
{{#complexarrayprint:team[0][name]}}          → Ann
{{#complexarrayprint:skills}}                 → php, sql, php, docker (as bullet list)
{{#complexarrayprint:skills|markup}}          → ["php","sql","php","docker"]
```

**3. Count** with `#complexarraysize`. Nested arrays are counted recursively unless `top` is given.

```
{{#complexarraysize:skills}}                  → 4
{{#complexarraysize:team}}                    → 9 (3 members and their 6 values)
{{#complexarraysize:team|top}}                → 3
```

**4. Change arrays.** `#complexarraypush` appends, `#complexarrayunset` removes a key (the list is
reindexed), `#complexarrayunique` removes duplicates (the first occurrence is kept) and `#complexarraysort`
sorts.

```
{{#complexarraypush:skills|js}}               skills: php, sql, php, docker, js
{{#complexarrayunset:skills[3]}}              skills: php, sql, php, js
{{#complexarrayunique:skills}}                skills: php, sql, js
{{#complexarraysort:team|keysort,desc|name}}  team: Cy, Bob, Ann
{{#complexarraysort:team|keysort|name}}       team: Ann, Bob, Cy
```

`#complexarrayaddvalue` sets the value at a path, creating the path if it does not exist. The value is stored
as a list:

```
{{#complexarraydefine:page|(("title": "Home"))}}
{{#complexarrayaddvalue:page[tags]|news}}
{{#complexarrayprint:page}}                   → title: Home, tags: news (nested list)
```

**5. Search.** `#complexarraysearch` returns the path of a value, `#complexarraysearcharray` stores the paths of
all matches in a new array, `#complexarrayparent` removes the last key of a path.

```
{{#complexarraysearch:skills|sql}}            → skills[1]
{{#complexarraysearcharray:devs|team|dev}}
{{#complexarrayprint:devs}}                   → team[0][role], team[2][role] (as bullet list)
{{#complexarrayparent:team[0][role]}}         → team[0]
```

**6. Create new arrays from existing ones.** The first argument is the name of the new array, the source
arrays are left unchanged.

```
{{#complexarrayslice:firstskills|skills|0|2}}      firstskills: php, sql   (offset 0, length 2)
{{#complexarrayextract:ann|team[0]}}               ann: name: Ann, role: dev
{{#complexarraydefine:have|php,sql,js}}
{{#complexarraydefine:need|php,go,js}}
{{#complexarraydiff:missing|need|have}}
{{#complexarrayprint:missing}}                     → 1: go   (positions that differ)
{{#complexarraymerge:all|have|need}}               all: php, sql, js, php, go, js
{{#complexarraypusharray:groups|have|need}}        groups: [have, need] as sub-arrays
```

`#complexarraymerge` needs at least two arrays; add `recursive` as last argument to combine the values of equal
keys instead of overwriting them. `#complexarraydiff` only works on one-dimensional arrays.

**7. Output the data.** `#complexarraymap` fills a pattern for every element. Arguments: array, mapping key,
pattern, separator, and `true` to keep the mapping key where an element has no such value.

```
{{#complexarraymap:team|@@@|@@@[name] works in @@@[role].|<br/>}}
→ Ann works in dev.
  Bob works in ops.
  Cy works in dev.
```

`#complexarraymaptemplate` calls a template for each member, passing the keys as named parameters. With
`Template:Person` containing `* {{{name}}} ({{{role}}})`, the call below expands to
`{{Person|name=Ann|role=dev}}` and so on; the last argument is the delimiter (`\n` and `\s` are supported).

```
{{#complexarraymaptemplate:team|Person||\n}}
```

`#complexarrayarraymap` maps a plain delimited list that is not stored as array. Arguments: list, delimiter,
mapping key, pattern, glue; `print=pretty` joins the result as "a, b and c".

```
{{#complexarrayarraymap:php,sql,js|,|####|Skill: ####|<br/>}}
{{#complexarrayarraymap:php,sql,js|,|####|####|print=pretty}}     → php, sql and js
```

**8. Inspect and clean up.** `#complexarraydefinedarrays` stores the names of all defined arrays in an array;
`#complexarrayreset` removes one array, or all arrays when called without a name.

```
{{#complexarraydefinedarrays:names}}
{{#complexarrayprint:names}}                  → team, skills, page, devs, ... (as bullet list)
{{#complexarrayreset:skills}}
{{#complexarrayreset:}}
```

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

Copyright (C) 2019 Marijn van Wezel
Copyright (C) 2026 gesinn.it GmbH & Co. KG (Alexander Gesinn)

ComplexArrays is free software, licensed under the GNU General Public License, version 2 or (at your option) any
later version (GPL-2.0-or-later). See [LICENSE](LICENSE) for the full text.
