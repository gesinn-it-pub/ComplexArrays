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
use PPFrame;

/**
 * Class ComplexArrayMap
 *
 * Defines the parser function {{#complexarraymap:}}, which allows users to iterate over (sub)arrays.
 */
class ComplexArrayMap extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraymap';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'camap'
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
	 * Buffer containing items to be returned.
	 *
	 * @var string
	 */
	private $buffer = '';

	/**
	 * Variable containing the name of the array that needs to be mapped.
	 *
	 * @var string
	 */
	private $array = '';

	/**
	 * Dynamic variable containing the key currently being worked on.
	 *
	 * @var string
	 */
	private $array_key = '';

	/**
	 * @var bool
	 */
	private $show = false;

	/**
	 * @var string
	 */
	private $sep = "";

	/**
	 * Define parameters and initialize parser. This parser is hooked with Parser::SFH_OBJECT_ARGS.
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $frame, $args ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		// Name
		if ( !isset( $args[0] ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		// Map key
		if ( !isset( $args[1] ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Map key' );
		}

		// Map
		if ( !isset( $args[2] ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Map' );
		}

		$call->show = isset( $args[4] ) ?
			filter_var( GlobalFunctions::getValue( $args[4], $frame ), FILTER_VALIDATE_BOOLEAN ) :
			false;

		if ( isset( $args[3] ) ) {
			$sep = GlobalFunctions::getValue( $args[3], $frame ) ?? '';

			if ( $sep === '\n' ) {
				$sep = "\r\n";
			}

			$call->sep = $sep;
		}

		$key_replace = isset( $args[5] ) ? GlobalFunctions::getValue( $args[5], $frame ) : false;

		$name = GlobalFunctions::getValue( $args[0] ?? null, $frame );
		$map_key = GlobalFunctions::getValue( $args[1] ?? null, $frame );
		$map = GlobalFunctions::getValue( $args[2] ?? null, $frame, $parser, 'NO_IGNORE,NO_TAGS,NO_TEMPLATES' );

		return [ $call->arrayMap( $parser, $name, $map_key, $map, $key_replace ), 'noparse' => false ];
	}

	/**
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $map_key
	 * @param string $map
	 * @param string|false $key_replace
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private function arrayMap( Parser $parser, $array_name, $map_key, $map, $key_replace = false ) {
		$this->buffer = '';

		if (
			GlobalFunctions::isBlank( $array_name )
			|| GlobalFunctions::isBlank( $map_key )
			|| GlobalFunctions::isBlank( $map )
		) {
			return '';
		}

		$base_array = GlobalFunctions::getBaseArrayFromArrayName( $array_name );
		$array = GlobalFunctions::getArrayFromArrayName( $parser, $array_name );

		if ( !ArrayStore::forParser( $parser )->has( $base_array ) || !$array ) {
			return '';
		}

		return $this->iterate( $parser, $array, $map_key, $map, $array_name, $key_replace );
	}

	/**
	 * @param Parser $parser
	 * @param array $array
	 * @param string $map_key
	 * @param string $map
	 * @param string $array_name
	 * @param string|false $key_replace
	 * @return string
	 */
	private function iterate( Parser $parser, $array, $map_key, $map, $array_name, $key_replace = false ) {
		$this->array = $array_name;

		$buffer = [];
		foreach ( $array as $array_key => $subarray ) {
			$current_map = $key_replace === false ? $map : str_replace( $key_replace, $array_key, $map );

			$this->array_key = $array_key;
			if ( gettype( $subarray ) !== "array" ) {
				$buffer[] = str_replace( $map_key, $subarray, $current_map );
			} else {
				$preg_quote = preg_quote( $map_key );
				$buffer[] = preg_replace_callback(
					"/($preg_quote((\[[^\[\]]+\])+)?)/",
					function ( $matches ) use ( $parser ) {
						return $this->replaceCallback( $parser, $matches );
					},
					$current_map
				);
			}
		}

		return $this->sep ? implode( $this->sep, $buffer ) : implode( $buffer );
	}

	/**
	 * @param Parser $parser
	 * @param array $matches
	 * @return string
	 *
	 * @throws Exception
	 */
	private function replaceCallback( Parser $parser, $matches ) {
		$value = $this->getValueFromMatch( $parser, $matches[0] );

		if ( is_string( $value ) || is_int( $value ) || is_float( $value ) ) {
			return (string)$value;
		}

		return $this->show ? $matches[0] : '';
	}

	/**
	 * @param Parser $parser
	 * @param string $match
	 * @return array|bool
	 *
	 * @throws Exception
	 */
	private function getValueFromMatch( Parser $parser, $match ) {
		$pointer = $this->getPointerFromArrayName( $match );
		$array_name = $this->getArrayNameFromPointer( $pointer );
		$value = GlobalFunctions::getArrayFromArrayName( $parser, $array_name );

		return $value;
	}

	/**
	 * @param string $pointer
	 * @return string
	 */
	private function getArrayNameFromPointer( $pointer ) {
		return $this->array . '[' . $this->array_key . ']' . $pointer;
	}

	/**
	 * @param string|int $array_key
	 * @return null|string|string[]
	 */
	private function getPointerFromArrayName( $array_key ) {
		return preg_replace( "/[^\[]*/", "", $array_key, 1 );
	}
}
