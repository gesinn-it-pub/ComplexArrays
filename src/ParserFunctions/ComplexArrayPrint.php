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

use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArrayPrint
 *
 * Defines the parser function {{#complexarrayprint:}}, which allows users to display an array in a couple of ways.
 */
class ComplexArrayPrint extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayprint';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'caprint'
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
	 * Holds the array being worked on.
	 *
	 * @var array
	 */
	private $array = [];

	/**
	 * @var string
	 */
	private $indent_char = "*";

	/**
	 * @var bool
	 */
	private $noparse = false;

	/**
	 * @var bool
	 */
	private $nowiki = false;

	/**
	 * Define all allowed parameters. This parser is hooked with Parser::SFH_OBJECT_ARGS.
	 *
	 * @param Parser $parser
	 * @param mixed $array_name
	 * @param mixed $options
	 * @param mixed $parser_behaviour
	 * @return null|string|array
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $array_name = null, $options = null, $parser_behaviour = null ) {
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		$call->array = [];

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		if ( $parser_behaviour === "true" ) {
			// Hack for backwards compatibility
			$call->noparse = true;
			$call->nowiki  = true;
		} else {
			$parser_behaviour_parts = explode( ",", (string)$parser_behaviour );
			$parser_behaviour_parts = array_map( "trim", $parser_behaviour_parts );

			$call->noparse = in_array( "noparse", $parser_behaviour_parts );
			$call->nowiki = in_array( "nowiki", $parser_behaviour_parts );
		}

		return $call->arrayPrint( $parser, $array_name, $options );
	}

	/**
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $options
	 * @return null|string
	 *
	 * @throws Exception
	 */
	private function arrayPrint( Parser $parser, $array_name, $options = '' ) {
		$this->array = GlobalFunctions::getArrayFromArrayName( $parser, $array_name );

		if ( $this->array === false ) {
			// Array does not exist
			return '';
		}

		if ( !GlobalFunctions::isBlank( $options ) ) {
			GlobalFunctions::serializeOptions( $options );
			$result = $this->applyOptions( $options );
		} else {
			$result = $this->createList();
		}

		return $result;
	}

	/**
	 * @param string|array $options
	 * @return array|mixed|null|string|string[]
	 */
	private function applyOptions( $options ) {
		if ( is_array( $options ) ) {
			$options = $options[ 0 ];
		}

		switch ( $options ) {
			case 'markup':
			case 'wson':
				return GlobalFunctions::arrayToMarkup( $this->array );
			default:
				return $this->createList();
		}
	}

	/**
	 * Create an (un)ordered list from an array.
	 *
	 * @return array|null|string
	 */
	private function createList() {
		if (
			!is_array( $this->array )
			|| ( count( $this->array ) === 1 && !GlobalFunctions::containsArray( $this->array ) )
		) {
			if ( is_array( $this->array ) ) {
				$last_el = reset( $this->array );
				$return  = key( $this->array ) . ": " . $last_el;

				return [ $return, 'noparse' => $this->noparse, 'nowiki' => $this->nowiki ];
			} else {
				// Replace any carraige returns with the empty string
				// TODO: Figure out where these cr's are coming from
				return [
					str_replace( "\r", "", $this->array ),
					'noparse' => $this->noparse,
					'nowiki' => $this->nowiki
				];
			}
		}

		$result = null;
		foreach ( $this->array as $key => $value ) {
			if ( !is_array( $value ) ) {
				$result .= is_numeric( $key )
					? $this->indent_char . " $value\n"
					: $this->indent_char . " $key: $value\n";
			} else {
				$result .= $this->indent_char . " " . strval( $key ) . "\n";
				$this->addArrayToList( $value, $result );
			}
		}

		return $result;
	}

	/**
	 * @param array $array
	 * @param string &$result
	 * @param int $depth
	 */
	private function addArrayToList( $array, &$result, $depth = 0 ) {
		$depth++;

		foreach ( $array as $key => $value ) {
			$indent = str_repeat( $this->indent_char, $depth + 1 );

			if ( !is_array( $value ) ) {
				if ( is_numeric( $key ) ) {
					$result .= "$indent $value\n";
				} else {
					$result .= "$indent $key: $value\n";
				}
			} else {
				$result .= "$indent " . strval( $key ) . "\n";

				$this->addArrayToList( $value, $result, $depth );
			}
		}
	}
}
