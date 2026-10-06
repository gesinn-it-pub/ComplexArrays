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
use Parser;

/**
 * Class ComplexArrayDefinedArrays
 *
 * Defines the parser function {{#complexarraydefinedarrays:}}, which allows users to get a list of defined arrays.
 */
class ComplexArrayDefinedArrays extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraydefinedarrays';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'cadefinedarrays',
			'cadefined',
			'cad'
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
	 * @param string|null $array_name
	 *
	 * @return array|string
	 */
	public static function getResult( Parser $parser, $array_name = null ) {
		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'New array' );
		}

		if ( !GlobalFunctions::isValidArrayName( $array_name ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		self::arrayDefinedArrays( $parser, $array_name );

		return '';
	}

	/**
	 * Store the list of defined array names as a new array.
	 *
	 * @param Parser $parser
	 * @param string $array_name
	 */
	private static function arrayDefinedArrays( Parser $parser, $array_name ) {
		$array = ArrayStore::forParser( $parser )->names();

		ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray( $array ) );
	}
}
