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
use ComplexArrays\ComplexArrays;
use ComplexArrays\GlobalFunctions;
use ComplexArrays\ResultPrinter;
use Exception;
use Parser;

/**
 * Class ComplexArraySort
 *
 * Defines the parser function {{#complexarraysort:}}, which allows users to sort arrays.
 */
class ComplexArraySort extends ResultPrinter {
	/**
	 * Get the name of the parser function.
	 *
	 * @return string
	 */
	public function getName() {
		return 'complexarraysort';
	}

	/**
	 * Get the aliases of the parser function.
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return [
			'casort'
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
	private static $key;

	/**
	 * @var string
	 */
	private static $array_name;

	/**
	 * @var array
	 */
	private static $array;

	/**
	 * Define all allowed parameters.
	 *
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $options
	 * @param string $key
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public static function getResult( Parser $parser, $array_name = '', $options = '', $key = '' ) {
		GlobalFunctions::fetchSemanticArrays();

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		return self::arraySort( $array_name, $options, $key );
	}

	/**
	 * @param string $array_name
	 * @param string $options
	 * @param string $key
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private static function arraySort( $array_name, $options = '', $key = '' ) {
		if ( !GlobalFunctions::arrayExists( $array_name ) ) {
			return '';
		}

		self::$array      = GlobalFunctions::getArrayFromComplexArray( ComplexArrays::$arrays[ $array_name ] );
		self::$array_name = $array_name;

		// The key is static, so it must not survive from a previous call.
		self::$key = $key !== '' ? $key : null;

		if ( GlobalFunctions::isBlank( $options ) ) {
			$result = self::sortArray( "sort" );
		} else {
			$result = self::sortArray( $options );
		}

		if ( $result === null ) {
			ComplexArrays::$arrays[$array_name] = new ComplexArray( self::$array );

			return '';
		}

		$key = array_shift( $result );

		return GlobalFunctions::error( $key, ...$result );
	}

	/**
	 * @param string $algo
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function sortArray( $algo ) {
		switch ( $algo ) {
			case 'multisort':
				$array = self::multisort();
				break;
			case 'asort':
				$array = self::asort();
				break;
			case 'arsort':
				$array = self::arsort();
				break;
			case 'krsort':
				$array = self::krsort();
				break;
			case 'natcasesort':
				$array = self::natcasesort();
				break;
			case 'natsort':
				$array = self::natsort();
				break;
			case 'rsort':
				$array = self::rsort();
				break;
			case 'shuffle':
				$array = self::shuffle();
				break;
			case 'keysort':
				$array = self::keysort( null );
				break;
			case 'keysort,desc':
				$array = self::keysort( 'desc' );
				break;
			case 'sort':
			default:
				$array = self::sort();
				break;
		}

		return $array;
	}

	/**
	 * Sort array using multisort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function multisort() {
		if ( !array_multisort( self::$array ) ) {
			return [ 'ca-sort-broken', 'multisort' ];
		}

		return null;
	}

	/**
	 * Sort array using asort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function asort() {
		asort( self::$array );

		return null;
	}

	/**
	 * Sort array using arsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function arsort() {
		arsort( self::$array );

		return null;
	}

	/**
	 * Sort array using krsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function krsort() {
		krsort( self::$array );

		return null;
	}

	/**
	 * Sort array using natcasesort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function natcasesort() {
		natcasesort( self::$array );

		return null;
	}

	/**
	 * Sort array using natsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function natsort() {
		natsort( self::$array );

		return null;
	}

	/**
	 * Sort array using rsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function rsort() {
		rsort( self::$array );

		return null;
	}

	/**
	 * Sort array using shuffle
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function shuffle() {
		shuffle( self::$array );

		return null;
	}

	/**
	 * Sort array using sort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function sort() {
		sort( self::$array );

		return null;
	}

	/**
	 * Sort array using keysort
	 *
	 * @param string $order
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private static function keysort( $order ) {
		if ( self::$key === null ) {
			return [ 'ca-sort-missing-key' ];
		}

		foreach ( self::$array as $value ) {
			if ( !isset( $value[ self::$key ] ) ) {
				return [ 'ca-sort-invalid-key' ];
			}

			if ( is_array( $value[ self::$key ] ) ) {
				return [ 'ca-sort-array-too-deep' ];
			}
		}

		self::ksort( self::$array, self::$key );

		$i = 0;
		$temp = [];
		foreach ( self::$array as $key => $item ) {
			$temp[ $i ] = $item;
			$i++;
		}

		self::$array = $temp;

		if ( $order == "desc" ) {
			self::$array = array_reverse( self::$array );
		}

		ComplexArrays::$arrays[ self::$array_name ] = new ComplexArray( self::$array );

		return null;
	}

	/**
	 * User-defined sorting function which sorts based on key.
	 *
	 * @param array &$array
	 * @param string $key
	 */
	private static function ksort( &$array, $key ) {
		$sorter = [];
		$ret = [];

		reset( $array );

		foreach ( $array as $ii => $va ) {
			$sorter[ $ii ] = $va[ $key ];
		}

		asort( $sorter );

		foreach ( $sorter as $ii => $va ) {
			$ret[ $ii ] = $array[ $ii ];
		}

		$array = $ret;
	}
}
