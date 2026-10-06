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

use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArrayArrayMap
 */
class ComplexArrayArrayMap extends ResultPrinter {
	/**
	 * @var array
	 */
	private static $array = [];

	/**
	 * @var string
	 */
	private static $variable = '';

	/**
	 * @var string
	 */
	private static $formula = '';

	/**
	 * @var string
	 */
	private static $new_delimiter = '';

	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarrayarraymap';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'caamap',
			'camapa'
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
	 * @param Parser $parser
	 * @param \PPFrame $frame
	 * @param array $args
	 * @throws Exception
	 *
	 * @return array
	 */
	public static function getResult( Parser $parser, $frame, $args ) {
		GlobalFunctions::fetchSemanticArrays();

		$value = GlobalFunctions::getValue(
			$args[0] ?? null,
			$frame,
			$parser,
			GlobalFunctions::getValue(
				$args[5] ?? null,
				$frame
			)
		);

		$delimiter = GlobalFunctions::getValue(
			$args[1] ?? null,
			$frame
		);

		$variable = GlobalFunctions::getValue(
			$args[2] ?? null,
			$frame
		);

		$formula = GlobalFunctions::getValue(
			$args[3] ?? null,
			$frame,
			$parser,
			'NO_IGNORE,NO_TAGS,NO_TEMPLATES'
		);

		$new_delimiter = GlobalFunctions::getValue(
			$args[4] ?? null,
			$frame
		);

		return [ self::arrayArrayMap( $value, $variable, $formula, $delimiter, $new_delimiter ), 'noparse' => false ];
	}

	/**
	 * @param mixed $value
	 * @param string $variable
	 * @param string $formula
	 * @param string $delimiter
	 * @param string $new_delimiter
	 * @return string
	 */
	private static function arrayArrayMap( $value, $variable, $formula, $delimiter, $new_delimiter ) {
		if (
			GlobalFunctions::isBlank( $value )
			|| GlobalFunctions::isBlank( $variable )
			|| GlobalFunctions::isBlank( $formula )
		) {
			return '';
		}

		if ( $delimiter === null || $delimiter === '' ) {
			$delimiter = ',';
		}

		if ( $new_delimiter === null ) {
			$new_delimiter = ', ';
		}

		$delimiter = str_replace( [ '\n', '\s' ], [ "\n", ' ' ], $delimiter );
		$new_delimiter = str_replace( [ '\n', '\s' ], [ "\n", ' ' ], $new_delimiter );

		self::$array         = array_map( "trim", explode( $delimiter, $value ) );
		self::$variable      = $variable;
		self::$formula       = $formula;
		self::$new_delimiter = $new_delimiter;

		$haystack = self::iterate();

		return $haystack;
	}

	/**
	 * Apply the formula to every item of the current array.
	 *
	 * @return string
	 */
	private static function iterate() {
		$haystack = [];

		foreach ( self::$array as $item ) {
			$replaced_formula = str_replace( self::$variable, $item, self::$formula );

			if ( $replaced_formula ) {
				array_push( $haystack, $replaced_formula );
			}
		}

		if ( self::$new_delimiter === "print=pretty" ) {
			return self::prettyPrint( $haystack );
		} else {
			return implode( self::$new_delimiter, $haystack );
		}
	}

	/**
	 * Join items into a human-readable list.
	 *
	 * @param array $haystack
	 * @return string
	 */
	private static function prettyPrint( $haystack ) {
		$num_items = count( $haystack );

		if ( $num_items === 0 ) {
			return "";
		} elseif ( $num_items === 1 ) {
			return array_pop( $haystack );
		}

		$last_element = array_pop( $haystack );
		$and = htmlspecialchars( wfMessage( "and" )->plain() );

		return implode( ", ", $haystack ) . $and . " " . $last_element;
	}
}
