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
 * Class ComplexArrayMerge
 *
 * Defines the parser function {{#complexarraymerge:}}, which allows users to merge multiple arrays.
 */
class ComplexArrayMerge extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraymerge';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'camerge'
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
	 * @var string
	 */
	private $new_array = '';

	/**
	 * @var string
	 */
	private $last_element = '';

	/**
	 * Define all allowed parameters.
	 *
	 * @param Parser $parser
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		return $call->arrayMerge( $parser, func_get_args() );
	}

	/**
	 * @param array $args
	 * @return array|string
	 * @throws Exception
	 */
	private function arrayMerge( Parser $parser, $args ) {
		$this->parseFunctionArguments( $args );

		if ( !GlobalFunctions::isValidArrayName( $this->new_array ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		if ( count( $args ) < 2 ) {
			return GlobalFunctions::error( 'ca-too-little-arrays' );
		}

		$arrays = $this->iterate( $parser, $args );

		if ( $this->last_element === "recursive" ) {
			$array = call_user_func_array( 'array_merge_recursive', $arrays );

			if ( is_array( $array ) ) {
				ArrayStore::forParser( $parser )->set( $this->new_array, new ComplexArray( $array ) );
			}
		} else {
			$array = call_user_func_array( 'array_merge', $arrays );

			if ( is_array( $array ) ) {
				ArrayStore::forParser( $parser )->set( $this->new_array, new ComplexArray( $array ) );
			}
		}

		return '';
	}

	/**
	 * @param array &$args
	 */
	private function parseFunctionArguments( &$args ) {
		$this->removeFirstItemFromArray( $args );
		$this->getFirstItemFromArray( $args );
		$this->removeFirstItemFromArray( $args );
		$this->removeLastItemFromArray( $args );

		// If the last element is not "recursive", add it back
		if ( $this->last_element !== "recursive" ) {
			$this->addItemToEndOfArray( $args, $this->last_element );
		}
	}

	/**
	 * @param array $arr
	 * @return array
	 * @throws Exception
	 */
	private function iterate( Parser $parser, $arr ) {
		$arrays = [];
		foreach ( $arr as $array ) {
			// Check if the array exists
			if ( !ArrayStore::forParser( $parser )->has( $array ) ) {
				continue;
			}

			$array = GlobalFunctions::getArrayFromComplexArray( ArrayStore::forParser( $parser )->get( $array ) );
			array_push( $arrays, (array)$array );
		}

		return $arrays;
	}

	/**
	 * @param array &$array
	 */
	private function removeFirstItemFromArray( &$array ) {
		array_shift( $array );
	}

	/**
	 * @param array &$array
	 */
	private function removeLastItemFromArray( &$array ) {
		$this->last_element = array_pop( $array );
	}

	/**
	 * @param array &$array
	 */
	private function getFirstItemFromArray( &$array ) {
		$this->new_array = reset( $array );
	}

	/**
	 * @param array &$array
	 * @param mixed $item
	 */
	private function addItemToEndOfArray( &$array, $item ) {
		array_push( $array, $item );
	}
}
