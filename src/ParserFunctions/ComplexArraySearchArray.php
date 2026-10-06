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
 * Class ComplexArraySearch
 *
 * Defines the parser function {{#complexarraysearcharray:}}, which allows users to search for a string in the
 * array, and define an array with all the keys of the result.
 */
class ComplexArraySearchArray extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraysearcharray';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'casearcharray',
			'casearcha'
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
	 * @var array[]|string[]
	 */
	private $found = [];

	/**
	 * @param Parser $parser
	 * @param string $new_array_name
	 * @param string $array_name
	 * @param string $value
	 * @return string|array
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $new_array_name = '', $array_name = '', $value = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		if ( GlobalFunctions::isBlank( $new_array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'New array key' );
		}

		if ( !GlobalFunctions::isValidArrayName( $new_array_name ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Array key' );
		}

		if ( GlobalFunctions::isBlank( $value ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Value' );
		}

		return $call->arraySearchArray( $parser, $new_array_name, $array_name, $value );
	}

	/**
	 * @param Parser $parser
	 * @param string $new_array
	 * @param string $name
	 * @param string $value
	 * @return string
	 *
	 * @throws Exception
	 */
	private function arraySearchArray( Parser $parser, $new_array, $name, $value ) {
		if ( !GlobalFunctions::arrayExists( $parser, $name ) ) {
			return '';
		}

		$found = $this->findValues( $parser, $value, $name );

		if ( $found !== [] ) {
			ArrayStore::forParser( $parser )->set( $new_array, new ComplexArray( $found ) );
		}

		return '';
	}

	/**
	 * @param Parser $parser
	 * @param string $value
	 * @param string $key
	 * @return string[]
	 *
	 * @throws Exception
	 */
	private function findValues( Parser $parser, $value, $key ) {
		$array = GlobalFunctions::getArrayFromArrayName( $parser, $key );

		$this->found = [];
		$this->i( $array, $value, $key );

		return $this->found;
	}

	/**
	 * @param array $array
	 * @param mixed $value
	 * @param string &$key
	 */
	private function i( $array, $value, &$key ) {
		foreach ( $array as $current_key => $current_item ) {
			$key .= "[$current_key]";

			if ( $value === $current_item ) {
				array_push( $this->found, $key );
			} else {
				if ( is_array( $current_item ) ) {
					$this->i( $current_item, $value, $key );
				}
			}

			$key = substr( $key, 0, strrpos( $key, '[' ) );
		}
	}
}
