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
use PPFrame;

/**
 * Class ComplexArrayDefine
 *
 * Defines the parser function {{#complexarraydefine:}}, which allows users to define a new array.
 */
class ComplexArrayDefine extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraydefine';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'cadefine'
		];
	}

	/**
	 * Get the type of the parser function.
	 *
	 * @return string
	 */
	public function getType() {
		return 'sfh';
	}

	/**
	 * Define all allowed parameters.
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 *
	 * @throws Exception
	 * @return array|string
	 */
	public static function getResult( Parser $parser, $frame, $args ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		// Name
		if ( GlobalFunctions::isBlank( $args[0] ?? null ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		$array_name   = GlobalFunctions::getValue( $args[0] ?? null, $frame );
		$noparse      = GlobalFunctions::getValue( $args[3] ?? null, $frame );
		$array_markup = GlobalFunctions::getValue( $args[1] ?? null, $frame, $parser, $noparse );
		$sep          = GlobalFunctions::getValue( $args[2] ?? null, $frame );

		if ( !GlobalFunctions::isValidArrayName( $array_name ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		// Define an empty array
		if ( GlobalFunctions::isBlank( $array_markup ) ) {
			ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray() );
		} else {
			$error = self::arrayDefine( $parser, $array_name, $array_markup, $sep );
			if ( $error !== null ) {
				return $error;
			}
		}

		return '';
	}

	/**
	 * Define array and store it in the array store of the parser.
	 *
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $array_markup
	 * @param string|null $separator
	 * @return array|null Error result, or null if the array was defined
	 * @throws Exception
	 */
	private static function arrayDefine( Parser $parser, $array_name, $array_markup, $separator = null ) {
		$array = GlobalFunctions::markupToArray( $array_markup, $separator );

		if ( $array === null ) {
			return GlobalFunctions::error( 'ca-invalid-markup' );
		}

		ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray( (array)$array ) );

		return null;
	}
}
