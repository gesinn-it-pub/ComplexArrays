<?php

/**
 * ComplexArrays - Associative and multidimensional arrays for MediaWiki.
 * Copyright (C) 2019 Marijn van Wezel
 * Copyright (C) 2026 gesinn.it GmbH & Co. KG (Alexander Gesinn)
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
 * Class ComplexArrayExtract
 *
 * Defines the parser function {{#complexarrayextract:}}, which allows users to create a new array from a subarray.
 */
class ComplexArrayExtract extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayextract';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'caextract'
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
	 * Define all allowed parameters.
	 *
	 * @param Parser $parser
	 * @param string $new_name
	 * @param string $array_name
	 * @return array|bool
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $new_name = '', $array_name = '' ) {
		if ( GlobalFunctions::isBlank( $new_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'New array' );
		}

		if ( !GlobalFunctions::isValidArrayName( $new_name ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Array key' );
		}

		return self::arrayExtract( $parser, $new_name, $array_name );
	}

	/**
	 * @param Parser $parser
	 * @param string $new_name
	 * @param string $array_name
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private static function arrayExtract( Parser $parser, $new_name, $array_name ) {
		// If no subarray is provided, show an error.
		if ( !strpos( $array_name, "[" ) ||
			!strpos( $array_name, "]" ) ) {
			return GlobalFunctions::error( 'ca-subarray-not-provided' );
		}

		$array = GlobalFunctions::getArrayFromArrayName( $parser, $array_name );

		if ( $array ) {
			ArrayStore::forParser( $parser )->set( $new_name, new ComplexArray( (array)$array ) );
		}

		return '';
	}
}
