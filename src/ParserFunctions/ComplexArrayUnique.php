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

use ComplexArrays\ComplexArray;
use ComplexArrays\ArrayStore;
use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArrayUnique
 *
 * Defines the parser function {{#complexarrayunique:}}, which allows users to remove duplicate keys or values
 * from a (sub)array.
 */
class ComplexArrayUnique extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayunique';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'caunique'
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
	 * @param string $array_name
	 * @return array|string
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $array_name = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Array key' );
		}

		if ( !GlobalFunctions::arrayExists( $parser, $array_name ) ) {
			return '';
		}

		self::arrayUnique( $parser, $array_name );

		return '';
	}

	/**
	 * Apply array_unique onto the array and safe it again as SafeComplexArray
	 *
	 * @param string $array_name
	 *
	 * @throws Exception
	 */
	private static function arrayUnique( Parser $parser, $array_name ) {
		$array = GlobalFunctions::getArrayFromComplexArray( ArrayStore::forParser( $parser )->get( $array_name ) );

		if ( GlobalFunctions::containsArray( $array ) ) {
			$array = array_unique( $array, SORT_REGULAR );

			ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray( $array ) );
		} else {
			$array = array_unique( $array );

			ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray( $array ) );
		}
	}
}
