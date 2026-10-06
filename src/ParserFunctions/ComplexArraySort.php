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
	private $key;

	/**
	 * @var string
	 */
	private $array_name;

	/**
	 * @var array
	 */
	private $array;

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
		GlobalFunctions::fetchSemanticArrays( $parser );

		$call = new self();

		if ( GlobalFunctions::isBlank( $array_name ) ) {
			return GlobalFunctions::error( 'ca-omitted', 'Name' );
		}

		return $call->arraySort( $parser, $array_name, $options, $key );
	}

	/**
	 * @param Parser $parser
	 * @param string $array_name
	 * @param string $options
	 * @param string $key
	 * @return array|string
	 *
	 * @throws Exception
	 */
	private function arraySort( Parser $parser, $array_name, $options = '', $key = '' ) {
		if ( !GlobalFunctions::arrayExists( $parser, $array_name ) ) {
			return '';
		}

		$this->array      = GlobalFunctions::getArrayFromComplexArray(
			ArrayStore::forParser( $parser )->get( $array_name )
		);
		$this->array_name = $array_name;

		// The key is static, so it must not survive from a previous call.
		$this->key = $key !== '' ? $key : null;

		if ( GlobalFunctions::isBlank( $options ) ) {
			$result = $this->sortArray( $parser, "sort" );
		} else {
			$result = $this->sortArray( $parser, $options );
		}

		if ( $result === null ) {
			ArrayStore::forParser( $parser )->set( $array_name, new ComplexArray( $this->array ) );

			return '';
		}

		$key = array_shift( $result );

		return GlobalFunctions::error( $key, ...$result );
	}

	/**
	 * @param Parser $parser
	 * @param string $algo
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function sortArray( Parser $parser, $algo ) {
		switch ( $algo ) {
			case 'multisort':
				$array = $this->multisort();
				break;
			case 'asort':
				$array = $this->asort();
				break;
			case 'arsort':
				$array = $this->arsort();
				break;
			case 'krsort':
				$array = $this->krsort();
				break;
			case 'natcasesort':
				$array = $this->natcasesort();
				break;
			case 'natsort':
				$array = $this->natsort();
				break;
			case 'rsort':
				$array = $this->rsort();
				break;
			case 'shuffle':
				$array = $this->shuffle();
				break;
			case 'keysort':
				$array = $this->keysort( $parser, null );
				break;
			case 'keysort,desc':
				$array = $this->keysort( $parser, 'desc' );
				break;
			case 'sort':
			default:
				$array = $this->sort();
				break;
		}

		return $array;
	}

	/**
	 * Sort array using multisort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function multisort() {
		if ( !array_multisort( $this->array ) ) {
			return [ 'ca-sort-broken', 'multisort' ];
		}

		return null;
	}

	/**
	 * Sort array using asort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function asort() {
		asort( $this->array );

		return null;
	}

	/**
	 * Sort array using arsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function arsort() {
		arsort( $this->array );

		return null;
	}

	/**
	 * Sort array using krsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function krsort() {
		krsort( $this->array );

		return null;
	}

	/**
	 * Sort array using natcasesort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function natcasesort() {
		natcasesort( $this->array );

		return null;
	}

	/**
	 * Sort array using natsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function natsort() {
		natsort( $this->array );

		return null;
	}

	/**
	 * Sort array using rsort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function rsort() {
		rsort( $this->array );

		return null;
	}

	/**
	 * Sort array using shuffle
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function shuffle() {
		shuffle( $this->array );

		return null;
	}

	/**
	 * Sort array using sort
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function sort() {
		sort( $this->array );

		return null;
	}

	/**
	 * Sort array using keysort
	 *
	 * @param Parser $parser
	 * @param string|null $order
	 *
	 * @return array|null The message key and parameters of an error, or null on success
	 */
	private function keysort( Parser $parser, $order ) {
		if ( $this->key === null ) {
			return [ 'ca-sort-missing-key' ];
		}

		foreach ( $this->array as $value ) {
			if ( !isset( $value[ $this->key ] ) ) {
				return [ 'ca-sort-invalid-key' ];
			}

			if ( is_array( $value[ $this->key ] ) ) {
				return [ 'ca-sort-array-too-deep' ];
			}
		}

		$this->ksort( $this->array, $this->key );

		$i = 0;
		$temp = [];
		foreach ( $this->array as $key => $item ) {
			$temp[ $i ] = $item;
			$i++;
		}

		$this->array = $temp;

		if ( $order == "desc" ) {
			$this->array = array_reverse( $this->array );
		}

		ArrayStore::forParser( $parser )->set( $this->array_name, new ComplexArray( $this->array ) );

		return null;
	}

	/**
	 * User-defined sorting function which sorts based on key.
	 *
	 * @param array &$array
	 * @param string $key
	 */
	private function ksort( &$array, $key ) {
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
