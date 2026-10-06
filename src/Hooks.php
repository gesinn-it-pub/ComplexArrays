<?php

namespace ComplexArrays;

use ComplexArrays\ParserFunctions\ComplexArrayAddValue;
use ComplexArrays\ParserFunctions\ComplexArrayArrayMap;
use ComplexArrays\ParserFunctions\ComplexArrayDefine;
use ComplexArrays\ParserFunctions\ComplexArrayDefinedArrays;
use ComplexArrays\ParserFunctions\ComplexArrayDiff;
use ComplexArrays\ParserFunctions\ComplexArrayExtract;
use ComplexArrays\ParserFunctions\ComplexArrayMap;
use ComplexArrays\ParserFunctions\ComplexArrayMapTemplate;
use ComplexArrays\ParserFunctions\ComplexArrayMerge;
use ComplexArrays\ParserFunctions\ComplexArrayParent;
use ComplexArrays\ParserFunctions\ComplexArrayPrint;
use ComplexArrays\ParserFunctions\ComplexArrayPushArray;
use ComplexArrays\ParserFunctions\ComplexArrayPushValue;
use ComplexArrays\ParserFunctions\ComplexArrayReset;
use ComplexArrays\ParserFunctions\ComplexArraySearch;
use ComplexArrays\ParserFunctions\ComplexArraySearchArray;
use ComplexArrays\ParserFunctions\ComplexArraySize;
use ComplexArrays\ParserFunctions\ComplexArraySlice;
use ComplexArrays\ParserFunctions\ComplexArraySort;
use ComplexArrays\ParserFunctions\ComplexArrayUnique;
use ComplexArrays\ParserFunctions\ComplexArrayUnset;
use MediaWiki\Hook\ParserClearStateHook;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\MediaWikiServices;
use Parser;

/**
 * Hook handlers of ComplexArrays.
 *
 * @license GPL-2.0-or-later
 */
class Hooks implements ParserFirstCallInitHook, ParserClearStateHook {

	/**
	 * The classes implementing a parser function, each providing getName(),
	 * getAliases(), getType() and getResult().
	 */
	private const PARSER_FUNCTIONS = [
		ComplexArrayAddValue::class,
		ComplexArrayArrayMap::class,
		ComplexArrayDefine::class,
		ComplexArrayDefinedArrays::class,
		ComplexArrayDiff::class,
		ComplexArrayExtract::class,
		ComplexArrayMap::class,
		ComplexArrayMapTemplate::class,
		ComplexArrayMerge::class,
		ComplexArrayParent::class,
		ComplexArrayPrint::class,
		ComplexArrayPushArray::class,
		ComplexArrayPushValue::class,
		ComplexArrayReset::class,
		ComplexArraySearch::class,
		ComplexArraySearchArray::class,
		ComplexArraySize::class,
		ComplexArraySlice::class,
		ComplexArraySort::class,
		ComplexArrayUnique::class,
		ComplexArrayUnset::class,
	];

	/**
	 * @param Parser $parser
	 * @return bool
	 */
	public function onParserFirstCallInit( $parser ) {
		foreach ( self::PARSER_FUNCTIONS as $class ) {
			$function = new $class();
			$flags = $function->getType() === 'sfh' ? Parser::SFH_OBJECT_ARGS : 0;

			foreach ( array_merge( [ $function->getName() ], $function->getAliases() ) as $name ) {
				$parser->setFunctionHook( $name, [ $class, 'getResult' ], $flags );
			}
		}

		return true;
	}

	/**
	 * Forgets the arrays of the previous parse.
	 *
	 * @param Parser $parser
	 */
	public function onParserClearState( $parser ) {
		ArrayStore::forParser( $parser )->clear();
	}

	/**
	 * Returns the arrays pre-defined via $wgDefinedArraysGlobal (name => array).
	 *
	 * @return array
	 */
	public static function getConfiguredArrays(): array {
		$arrays = self::getConfig( 'DefinedArraysGlobal', 'wfDefinedArraysGlobal' );
		return is_array( $arrays ) ? $arrays : [];
	}

	/**
	 * Reads an option from MediaWiki's configuration ($wg<name>). The legacy global
	 * $wf... is a deprecated fallback, used only if the option was not configured.
	 *
	 * @param string $name Option name without the "wg" prefix
	 * @param string $legacyGlobal Name of the deprecated global
	 * @return mixed
	 */
	private static function getConfig( string $name, string $legacyGlobal ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$value = $config->get( $name );

		if ( ( $value === [] || $value === false ) && isset( $GLOBALS[$legacyGlobal] ) ) {
			wfDebugLog(
				'ComplexArrays',
				'$' . $legacyGlobal . ' is deprecated, use $wg' . $name . ' instead.'
			);
			return $GLOBALS[$legacyGlobal];
		}

		return $value;
	}
}
