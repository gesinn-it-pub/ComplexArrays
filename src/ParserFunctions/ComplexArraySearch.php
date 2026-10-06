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
use Exception;
use Parser;

/**
 * Class ComplexArraySearch
 *
 * Defines the parser function {{#complexarraysearch:}}, which allows users to get search in an array.
 */
class ComplexArraySearch extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraysearch';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
		  'casearch'
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
	 * @var string|null
	 */
	private $array_name = '';

	/**
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $value
	 * @return array
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $array_name = '', $value = '' ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		if ( $array_name === '' ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		if ( $value === '' ) {
			return GlobalFunctions::error( 'ca-omitted', 'Value' );
		}

		return $call->arraySearch( $parser, $array_name, $value );
	}

	/**
	 * @param string $array_name
	 * @param mixed $value
	 * @return array|int|string
	 *
	 * @throws Exception
	 */
	private function arraySearch( Parser $parser, $array_name, $value ) {
		if ( !ArrayStore::forParser( $parser )->has( $array_name ) ) {
			return '';
		}

		$this->array_name = null;

		$array = GlobalFunctions::getArrayFromArrayName( $parser, $array_name );
		$this->findValue( $array, $value, $array_name );

		return $this->array_name;
	}

	/**
	 * @param array $array
	 * @param string $value
	 * @param string &$array_name
	 */
	private function findValue( $array, $value, &$array_name ) {
		foreach ( $array as $current_key => $current_item ) {
			$array_name .= "[$current_key]";

			if ( $value === $current_item ) {
				$this->array_name = $array_name;

				return;
			} else {
				if ( is_array( $current_item ) ) {
					$this->findValue( $current_item, $value, $array_name );
				}

				$array_name = substr( $array_name, 0, strrpos( $array_name, '[' ) );
			}
		}
	}
}
