Multidimensional and associative arrays for MediaWiki.

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

ComplexArrays - Associative and multidimensional arrays for MediaWiki.
Copyright (C) 2019 Marijn van Wezel

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.