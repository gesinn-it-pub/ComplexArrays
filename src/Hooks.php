<?php

namespace ComplexArrays;

use ComplexArrays\ParserFunctionsComplexArrayAddValue;
use ComplexArrays\ParserFunctionsComplexArrayArrayMap;
use ComplexArrays\ParserFunctionsComplexArrayDefine;
use ComplexArrays\ParserFunctionsComplexArrayDefinedArrays;
use ComplexArrays\ParserFunctionsComplexArrayDiff;
use ComplexArrays\ParserFunctionsComplexArrayExtract;
use ComplexArrays\ParserFunctionsComplexArrayMap;
use ComplexArrays\ParserFunctionsComplexArrayMapTemplate;
use ComplexArrays\ParserFunctionsComplexArrayMerge;
use ComplexArrays\ParserFunctionsComplexArrayParent;
use ComplexArrays\ParserFunctionsComplexArrayPrint;
use ComplexArrays\ParserFunctionsComplexArrayPushArray;
use ComplexArrays\ParserFunctionsComplexArrayPushValue;
use ComplexArrays\ParserFunctionsComplexArrayReset;
use ComplexArrays\ParserFunctionsComplexArraySearch;
use ComplexArrays\ParserFunctionsComplexArraySearchArray;
use ComplexArrays\ParserFunctionsComplexArraySize;
use ComplexArrays\ParserFunctionsComplexArraySlice;
use ComplexArrays\ParserFunctionsComplexArraySort;
use ComplexArrays\ParserFunctionsComplexArrayUnique;
use ComplexArrays\ParserFunctionsComplexArrayUnset;
use MediaWiki\Hook\ParserFirstCallInitHook;
use Parser;

/**
 * Hook handlers of ComplexArrays.
 *
 * @license GPL-2.0-or-later
 */
class Hooks implements ParserFirstCallInitHook {

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
		$this->registerResultPrinter();

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
	 * Registers the "complexarray" result format of Semantic MediaWiki, if enabled.
	 */
	private function registerResultPrinter(): void {
		$link = $GLOBALS['wgExtensionDirectory'] . '/SemanticMediaWiki/src/Query/ResultPrinters/ComplexArrayPrinter.php';
		$target = dirname( __DIR__ ) . '/ComplexArrayPrinter.php';

		if ( @$GLOBALS['wfEnableResultPrinter'] !== true ) {
			return;
		}

		if ( !file_exists( $link ) ) {
			if ( !file_exists( $target ) ) {
				return;
			}

			if ( !symlink( $target, $link ) ) {
				wfDebugLog( 'ComplexArrays', 'Creation of symbolic link from target ' . $target . ' to link ' . $link . ' failed.' );
				return;
			}
		}

		$GLOBALS['smwgResultFormats']['complexarray'] = 'SMW\\Query\\ResultPrinters\\ComplexArrayPrinter';
	}
}
