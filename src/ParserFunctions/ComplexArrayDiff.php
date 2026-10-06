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
 * Class ComplexArrayDiff
 *
 * Defines the parser function {{#complexarraydiff:}}, which calculates the difference between two arrays.
 */
class ComplexArrayDiff extends ResultPrinter {
	/**
	 * @var string
	 */
	private $new_array;

	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraydiff';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'cadiff'
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
	 * @return array|int
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		return $call->arrayDiff( $parser, func_get_args() );
	}

	/**
	 * Calculate difference between arrays.
	 *
	 * @param array $args
	 *
	 * @return array|string
	 * @throws Exception
	 */
	private function arrayDiff( Parser $parser, $args ) {
		$this->parseFunctionArguments( $args );

		if ( !GlobalFunctions::isValidArrayName( $this->new_array ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		$arrays = $this->pushArrays( $parser, $args );

		if ( count( $arrays ) < 2 ) {
			return GlobalFunctions::error( 'ca-too-little-arrays' );
		}

		foreach ( $arrays as $array ) {
			if ( !is_array( $array ) ) {
				return '';
			}

			if ( !$this->isOneDimensionalArray( $array ) ) {
				return GlobalFunctions::error( 'ca-diff-multidimensional' );
			}
		}

		$array_diff = call_user_func_array( 'array_diff_assoc', $arrays );

		if ( is_array( $array_diff ) ) {
			ArrayStore::forParser( $parser )->set( $this->new_array, new ComplexArray( $array_diff ) );
		}

		return '';
	}

	/**
	 * @param array $arr
	 * @return array
	 * @throws Exception
	 */
	private function pushArrays( Parser $parser, $arr ) {
		$arrays = [];

		foreach ( $arr as $array ) {
			// Check if the array exists
			if ( !ArrayStore::forParser( $parser )->has( $array ) ) {
				continue;
			}

			$array = GlobalFunctions::getArrayFromComplexArray( ArrayStore::forParser( $parser )->get( $array ) );

			array_push( $arrays, $array );
		}

		return $arrays;
	}

	/**
	 * @param array &$args
	 */
	private function parseFunctionArguments( &$args ) {
		$this->removeFirstItemFromArray( $args );
		$this->getFirstItemFromArray( $args );
		$this->removeFirstItemFromArray( $args );
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
	private function getFirstItemFromArray( &$array ) {
		$this->new_array = reset( $array );
	}

	/**
	 * Check whether an array contains no nested arrays.
	 *
	 * @param array $array
	 * @return bool
	 */
	private function isOneDimensionalArray( array $array ) {
		foreach ( $array as $item ) {
			if ( is_array( $item ) ) {
				return false;
			}
		}

		return true;
	}
}
