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

use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArraySize
 *
 * Defines the parser function {{#complexarraysize:}}, which allows users to get the size of a (sub)array.
 */
class ComplexArraySize extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraysize';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
		  'casize'
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
	 * @param string $array_name
	 * @param string $options
	 * @return array|int
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $array_name = '', $options = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Array key' );
		}

		return self::arraySize( $parser, $array_name, $options );
	}

	/**
	 * Calculate size of array.
	 *
	 * @param string $name
	 * @param string $options
	 * @return array|int|string
	 *
	 * @throws Exception
	 */
	private static function arraySize( Parser $parser, $name, $options = '' ) {
		if ( !GlobalFunctions::arrayExists( $parser, GlobalFunctions::getBaseArrayFromArrayName( $name ) ) ) {
			return '';
		}

		$array = GlobalFunctions::getArrayFromArrayName( $parser, $name );

		if ( $options === "top" ) {
			return count( $array );
		}

		return count( $array, COUNT_RECURSIVE );
	}
}
