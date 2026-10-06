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
use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Parser;

/**
 * Class ComplexArrayReset
 *
 * Defines the parser function {{#complexarrayreset:}}, which allows users to reset all or one array.
 */
class ComplexArrayReset extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayreset';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'careset'
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
	 * @return string
	 */
	public static function getResult( Parser $parser, $array_name = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		self::arrayReset( $parser, $array_name );
		return '';
	}

	/**
	 * Reset all or one array.
	 *
	 * @param Parser $parser
	 * @param string $array_name
	 */
	private static function arrayReset( Parser $parser, $array_name = '' ) {
		if ( GlobalFunctions::isBlank( $array_name ) ) {
			ArrayStore::forParser( $parser )->clear();
		} else {
			if ( ArrayStore::forParser( $parser )->has( $array_name ) ) {
				ArrayStore::forParser( $parser )->remove( $array_name );
			}
		}
	}
}
