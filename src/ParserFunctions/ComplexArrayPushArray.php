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
use ComplexArrays\ComplexArray;
use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArrayPushArray
 *
 * Defines the parser function {{#complexarraypusharray:}}, which allows users to push one or more arrays to the
 * end of another array, creating a new array.
 */
class ComplexArrayPushArray extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraypusharray';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'capusharray'
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
	 * Define parameters and initialize parser.
	 *
	 * @param Parser $parser
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		return $call->arrayPush( $parser, func_get_args() );
	}

	/**
	 * @param Parser $parser
	 * @param array $args
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private function arrayPush( Parser $parser, $args ) {
		$this->parseFunctionArguments( $args );

		if ( !GlobalFunctions::isValidArrayName( $this->new_array ) ) {
			return GlobalFunctions::error( 'ca-invalid-name' );
		}

		if ( count( $args ) < 2 ) {
			return GlobalFunctions::error( 'ca-too-little-arrays' );
		}

		$arrays = $this->iterate( $parser, $args );

		ArrayStore::forParser( $parser )->set( $this->new_array, new ComplexArray( $arrays ) );

		return '';
	}

	/**
	 * @param Parser $parser
	 * @param array $array
	 * @return array|bool
	 *
	 * @throws Exception
	 */
	private function iterate( Parser $parser, $array ) {
		$arrays = [];
		foreach ( $array as $array_name ) {
			if ( !GlobalFunctions::arrayExists( $parser, $array_name ) ) {
				continue;
			}

			$push_array = GlobalFunctions::getArrayFromComplexArray(
				ArrayStore::forParser( $parser )->get( $array_name )
			);

			array_push( $arrays, $push_array );
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
}
