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

namespace SMW\Query\ResultPrinters;

/**
 * Class ComplexArrayPrinter
 *
 * @package SMW\Query\ResultPrinters
 * @extends ResultPrinter
 */
class ComplexArrayPrinter extends ResultPrinter {
	/**
	 * @var string
	 */
	private $name = '';

	/**
	 * @var string
	 */
	private $delimiter = ',';

	/**
	 * @var bool
	 */
	private $detailed = false;

	/**
	 * @var array
	 */
	private $r = [];

	/**
	 * @var array
	 */
	private $v = [];

	/**
	 * @var array
	 */
	private $res = [];

	/**
	 * @var array
	 */
	private $return = [];

	/**
	 * Define the name of the format.
	 *
	 * @return string
	 */
	public function getName() {
		return "ComplexArray";
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	public function getParamDefinitions( array $definitions ) {
		$definitions = parent::getParamDefinitions( $definitions );

		$definitions[] = [
			'name' => 'name',
			'message' => 'ca-smw-paramdesc-name',
			'default' => ''
		];

		$definitions[] = [
			'name' => 'detailed',
			'message' => 'ca-smw-paramdesc-detailed',
			'default' => 'false'
		];

		$definitions[] = [
			'name' => 'valuesep',
			'message' => 'ca-smw-paramdesc-valuesep',
			'default' => ','
		];

		return $definitions;
	}

	/**
	 * Creates an empty array with the specified name before the result is passed to getResultText().
	 *
	 * @inheritDoc
	 */
	protected function handleParameters( array $params, $outputMode ) {
		$name = $params['name'];

		global $wgComplexArraysDefinedArrays;
		$wgComplexArraysDefinedArrays[ $name ] = new \ComplexArrays\ComplexArray( [] );
	}

	/**
	 * @param \SMWQueryResult $queryResult
	 * @param int $outputMode
	 * @return bool|string
	 */
	protected function getResultText( \SMWQueryResult $queryResult, $outputMode ) {
		return $this->buildContents( $queryResult );
	}

	/**
	 * @param \SMWQueryResult $queryResult
	 * @return bool|string
	 */
	private function buildContents( \SMWQueryResult $queryResult ) {
		global $wgComplexArraysDefinedArrays;

		$this->name = $this->params[ 'name' ];
		$this->delimiter = $this->params[ 'valuesep' ];
		$this->detailed = filter_var( $this->params[ 'detailed' ], FILTER_VALIDATE_BOOLEAN );

		if ( !$this->name ) {
			$json = json_encode( $this->buildResultArray( $queryResult ) );

			$json = preg_replace( "/(?!\B\"[^\"]*){(?![^\"]*\"\B)/i", "((", $json );
			$json = preg_replace( "/(?!\B\"[^\"]*)}(?![^\"]*\"\B)/i", "))", $json );

			return $json;
		}

		$result = $this->buildResultArray( $queryResult );

		$wgComplexArraysDefinedArrays[ $this->name ] = new \ComplexArrays\ComplexArray( $result );

		return null;
	}

	/**
	 * @param \SMWQueryResult $res
	 * @return array
	 */
	private function buildResultArray( \SMWQueryResult $res ) {
		$this->res = array_merge( $res->serializeToArray() );

		foreach ( $this->res['results'] as $result ) {
			$this->r = [];
			$this->formatResult( $result );
		}

		return $this->return;
	}

	/**
	 * Format a single query result row and append it to the result list.
	 *
	 * @param array $result
	 */
	private function formatResult( $result ) {
		foreach ( $result["printouts"] as $key => $printout ) {
			$this->formatPrintout( $key, $printout );
		}

		if ( isset( $result[ 'fulltext' ] ) ) {
			$this->r[ 'catitle' ] = $result[ 'fulltext' ];
		}

		if ( isset( $result[ 'fullurl' ] ) ) {
			$this->r[ 'cafullurl' ] = $result[ 'fullurl' ];
		}

		if ( isset( $result[ 'namespace' ] ) ) {
			$this->r[ 'canamespace' ] = $result[ 'namespace' ];
		}

		if ( isset( $result[ 'exists' ] ) ) {
			$this->r[ 'caexists' ] = $result[ 'exists' ];
		}

		if ( isset( $result[ 'displaytitle' ] ) ) {
			$this->r[ 'cadisplaytitle' ] = $result[ 'displaytitle' ];
		}

		array_push( $this->return, $this->r );
	}

	/**
	 * @param string $key
	 * @param array $printout
	 */
	private function formatPrintout( $key, $printout ) {
		$this->v = [];

		$prop_type = $this->fetchPropType( $key );
		foreach ( $printout as $property ) {
			$this->formatProperty( $prop_type, $property );
		}

		$this->addPrintout( $key );
	}

	/**
	 * @param string $key
	 */
	private function addPrintout( $key ) {
		if ( !empty( $this->v ) ) {
			if ( count( $this->v ) === 1 ) {
				$this->r[$key] = $this->v[0];
			} else {
				$this->r[$key] = $this->v;
			}
		}
	}

	/**
	 * @param string $prop_type
	 * @param mixed $property
	 */
	private function formatProperty( $prop_type, $property ) {
		switch ( $prop_type ) {
			case "_wpg":
				array_push( $this->v, $this->formatPropertyOfTypeWpg( $property ) );
				break;
			case "_dat":
				array_push( $this->v, $this->formatPropertyOfTypeDat( $property ) );
				break;
			case "_ema":
				array_push( $this->v, $this->formatPropertyOfTypeEma( $property ) );
				break;
			case "_boo":
				array_push( $this->v, $this->formatPropertyOfTypeBoo( $property ) );
				break;
			default:
				array_push( $this->v, $this->formatPropertyOfTypeTxt( $property ) );
				break;
		}
	}

	/**
	 * Format property values of type _wpg (page).
	 *
	 * @param string|array $property
	 * @return string|array
	 */
	private function formatPropertyOfTypeWpg( $property ) {
		if ( $this->detailed === true && isset( $property['fulltext'] ) ) {
			return $property['fulltext'];
		}

		return $property;
	}

	/**
	 * Format property values of type _dat (date).
	 *
	 * @param array $property
	 * @return string
	 */
	private function formatPropertyOfTypeDat( $property ) {
		$unix_timestamp = $property["timestamp"];

		// Return ISO 8601 timestamp
		return date( 'c', $unix_timestamp );
	}

	/**
	 * Format property values of type _ema (email).
	 *
	 * @param string $property
	 * @return string
	 */
	private function formatPropertyOfTypeEma( $property ) {
		return str_replace( "mailto:", "", $property );
	}

	/**
	 * Format property values of type _boo (boolean).
	 *
	 * @param string $property
	 * @return string
	 */
	private function formatPropertyOfTypeBoo( $property ) {
		switch ( $property ) {
			case 't':
				return '1';
			case 'f':
				return '0';
		}

		return $property;
	}

	/**
	 * This function is not really necessary, it is just here for proper semantics.
	 *
	 * @param string $property
	 * @return string
	 */
	private function formatPropertyOfTypeTxt( $property ) {
		return $property;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private function fetchPropType( $key ) {
		$print_requests = $this->res["printrequests"];

		foreach ( $print_requests as $print_request ) {
			if ( $print_request["label"] === $key ) {
				return $print_request["typeid"];
			}
		}

		// When the property isn't found (should never happen) assume _txt.
		return "_txt";
	}
}
