<?php

/**
 * ComplexArrays - Associative and multidimensional arrays for MediaWiki.
 * Copyright (C) 2019 Marijn van Wezel
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace ComplexArrays\ParserFunctions;

use ComplexArrays\ArrayStore;
use ComplexArrays\ComplexArray;
use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArrayAddValue
 *
 * Defines the parser function {{#complexarrayaddvalue:}}, which allows users to add values to (sub)arrays.
 */
class ComplexArrayAddValue extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayaddvalue';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'caaddvalue',
			'caadd',
			'caaddv',
			'caset'
		];
	}

	/**
	 * Get the type of the parser function.
	 *
	 * @return string
	 */
	public function getType() {
		return 'normal';
	}

	/**
	 * Define parameters and initialize parser.
	 *
	 * @param Parser $parser
	 * @param string $name
	 * @param string $value
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $name = '', $value = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		if ( GlobalFunctions::isBlank( $name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		if ( GlobalFunctions::isBlank( $value ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Value' );
		}

		if ( !strpos( $name, "[" ) ||
			 !strpos( $name, "]" ) ) {
			return GlobalFunctions::error( 'ca-subarray-not-provided' );
		}

		return self::arrayAddValue( $parser, $name, $value );
	}

	/**
	 * This function first calculates the name of the base array, then fetches that array and adds a value to the array.
	 * The array is then saved again under the same name with the value added.
	 *
	 * @param Parser $parser
	 * @param string $array_name
	 * @param mixed $value
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private static function arrayAddValue( Parser $parser, $array_name, $value ) {
		$base_array_name = GlobalFunctions::getBaseArrayFromArrayName( $array_name );

		if ( !GlobalFunctions::arrayExists( $parser, $base_array_name ) ) {
			return '';
		}

		$keys = GlobalFunctions::getKeys( $array_name );

		if ( !$keys ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		$array = GlobalFunctions::getArrayFromComplexArray( ArrayStore::forParser( $parser )->get( $base_array_name ) );

		self::set( $keys, $array, $value );

		ArrayStore::forParser( $parser )->set( $base_array_name, new ComplexArray( $array ) );

		return '';
	}

	/**
	 * @param array $path
	 * @param array &$array
	 * @param string $value
	 */
	private static function set( $path, &$array, $value ) {
		$value = GlobalFunctions::markupToArray( $value );

		$temp =& $array;

		foreach ( $path as $key ) {
			if ( !isset( $temp[$key] ) ) {
				$temp[$key] = [];
			}

			$temp =& $temp[$key];
		}

		$temp = $value;
	}
}
