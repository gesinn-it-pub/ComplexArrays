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

namespace ComplexArrays\SMW;

use ComplexArrays\ArrayStore;
use ComplexArrays\ComplexArray;
use SMW\Query\QueryResult;
use SMW\Query\ResultPrinters\ResultPrinter;

/**
 * The "complexarray" result format of Semantic MediaWiki.
 *
 * Turns the result of a query into a list of associative arrays, one per result
 * row. With the "name" parameter the list is defined as a ComplexArray of that
 * name, otherwise it is printed as JSON ready to be passed to #complexarraydefine.
 *
 * Only loaded by Semantic MediaWiki, see Hooks.
 *
 * @license GPL-2.0-or-later
 */
class ComplexArrayPrinter extends ResultPrinter {

	/**
	 * @return string
	 */
	public function getName() {
		return "ComplexArray";
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	public function getParamDefinitions( array $definitions ): array {
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

		// Accepted for backwards compatibility; it has no effect.
		$definitions[] = [
			'name' => 'valuesep',
			'message' => 'ca-smw-paramdesc-valuesep',
			'default' => ','
		];

		return $definitions;
	}

	/**
	 * @param QueryResult $queryResult
	 * @param int $outputMode
	 * @return string
	 */
	protected function getResultText( QueryResult $queryResult, $outputMode ) {
		$detailed = filter_var( $this->params['detailed'], FILTER_VALIDATE_BOOLEAN );
		$rows = $this->buildRows( $queryResult->serializeToArray(), $detailed );

		if ( !$this->params['name'] ) {
			$json = json_encode( $rows );

			$json = preg_replace( "/(?!\B\"[^\"]*){(?![^\"]*\"\B)/i", "((", $json );
			return preg_replace( "/(?!\B\"[^\"]*)}(?![^\"]*\"\B)/i", "))", $json );
		}

		// The parser that runs the query, so the array is visible to the rest of the page.
		ArrayStore::forParser( $this->copyParser() )->set( $this->params['name'], new ComplexArray( $rows ) );

		return '';
	}

	/**
	 * @param array $serialized The serialized query result
	 * @param bool $detailed
	 * @return array[]
	 */
	private function buildRows( array $serialized, bool $detailed ): array {
		$types = [];
		foreach ( $serialized['printrequests'] as $printRequest ) {
			$types[$printRequest['label']] = $printRequest['typeid'];
		}

		$rows = [];
		foreach ( $serialized['results'] as $result ) {
			$rows[] = $this->buildRow( $result, $types, $detailed );
		}

		return $rows;
	}

	/**
	 * @param array $result One serialized result row
	 * @param string[] $types Type id by printout label
	 * @param bool $detailed
	 * @return array
	 */
	private function buildRow( array $result, array $types, bool $detailed ): array {
		$row = [];

		foreach ( $result['printouts'] as $key => $printout ) {
			// When the property isn't found (should never happen) assume _txt.
			$type = $types[$key] ?? '_txt';
			$values = [];

			foreach ( $printout as $property ) {
				$values[] = $this->formatValue( $type, $property, $detailed );
			}

			if ( $values ) {
				$row[$key] = count( $values ) === 1 ? $values[0] : $values;
			}
		}

		$extra = [
			'fulltext' => 'catitle',
			'fullurl' => 'cafullurl',
			'namespace' => 'canamespace',
			'exists' => 'caexists',
			'displaytitle' => 'cadisplaytitle',
		];

		foreach ( $extra as $from => $to ) {
			if ( isset( $result[$from] ) ) {
				$row[$to] = $result[$from];
			}
		}

		return $row;
	}

	/**
	 * @param string $type SMW type id
	 * @param string|array $value
	 * @param bool $detailed
	 * @return string|array
	 */
	private function formatValue( string $type, $value, bool $detailed ) {
		switch ( $type ) {
			case '_wpg':
				return $detailed && isset( $value['fulltext'] ) ? $value['fulltext'] : $value;
			case '_dat':
				// ISO 8601
				return date( 'c', $value['timestamp'] );
			case '_ema':
				return str_replace( 'mailto:', '', $value );
			case '_boo':
				return [ 't' => '1', 'f' => '0' ][$value] ?? $value;
			default:
				return $value;
		}
	}
}
