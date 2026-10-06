<?php
/**
 * This is an automatically generated baseline for Phan issues.
 * When Phan is invoked with --load-baseline=path/to/baseline.php,
 * The pre-existing issues listed in this file won't be emitted.
 *
 * This file can be updated by invoking Phan with --save-baseline=path/to/baseline.php
 * (can be combined with --load-baseline)
 */
return [
	// # Issue statistics:
	// PhanTypeMismatchArgument : 45+ occurrences
	// MediaWikiNoEmptyIfDefined : 30+ occurrences
	// PhanTypeMismatchReturn : 15+ occurrences
	// PhanRedundantCondition : 8 occurrences
	// PhanTypeMismatchArgumentProbablyReal : 5 occurrences
	// PhanCommentParamWithoutRealParam : 3 occurrences
	// PhanNonClassMethodCall : 3 occurrences
	// PhanPluginUnreachableCode : 3 occurrences
	// PhanUnextractableAnnotation : 3 occurrences
	// PhanTypeMismatchPropertyProbablyReal : 2 occurrences
	// MediaWikiNoIssetIfDefined : 1 occurrence
	// PhanCommentParamOutOfOrder : 1 occurrence
	// PhanImpossibleValueComparison : 1 occurrence
	// PhanTypeMismatchArgumentInternal : 1 occurrence
	// PhanTypeMismatchReturnProbablyReal : 1 occurrence
	// PhanTypeSuspiciousStringExpression : 1 occurrence

	'file_suppressions' => [
	    'src/ComplexArrayWrapper.php' => [
	        'PhanTypeMismatchPropertyProbablyReal' => ['\\ComplexArrays\\ComplexArrayWrapper::reset']
	    ],
	    'src/GlobalFunctions.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\GlobalFunctions::getValue'],
	        'MediaWikiNoIssetIfDefined' => ['\\ComplexArrays\\GlobalFunctions::getValue'],
	        'PhanCommentParamWithoutRealParam' => ['\\ComplexArrays\\GlobalFunctions::getArrayFromArrayName', '\\ComplexArrays\\GlobalFunctions::getSubarrayFromArrayName'],
	        'PhanNonClassMethodCall' => ['\\ComplexArrays\\GlobalFunctions::getSFHValue', '\\ComplexArrays\\GlobalFunctions::rawValue'],
	        'PhanTypeMismatchReturnProbablyReal' => ['\\ComplexArrays\\GlobalFunctions::getValue']
	    ],
	    'src/ParserFunctions/ComplexArrayAddValue.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::arrayAddValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::set']
	    ],
	    'src/ParserFunctions/ComplexArrayArrayMap.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayArrayMap::arrayArrayMap'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayArrayMap::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDefine.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::arrayDefine', '\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDefinedArrays.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefinedArrays::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefinedArrays::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDiff.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDiff::arrayDiff']
	    ],
	    'src/ParserFunctions/ComplexArrayExtract.php' => [
	        'PhanCommentParamWithoutRealParam' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract', '\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract']
	    ],
	    'src/ParserFunctions/ComplexArrayMap.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::arrayMap'],
	        'PhanPluginUnreachableCode' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::replaceCallback'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getResult'],
	        'PhanTypeMismatchArgumentInternal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::iterate'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::replaceCallback']
	    ],
	    'src/ParserFunctions/ComplexArrayMapTemplate.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::getResult'],
	        'PhanTypeSuspiciousStringExpression' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::map']
	    ],
	    'src/ParserFunctions/ComplexArrayMerge.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMerge::arrayMerge']
	    ],
	    'src/ParserFunctions/ComplexArrayParent.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayPrint.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::arrayPrint', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::getResult'],
	        'PhanPluginUnreachableCode' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::applyOptions'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayPushArray.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushArray::arrayPush']
	    ],
	    'src/ParserFunctions/ComplexArrayPushValue.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::arrayPushValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::getResult'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayReset.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayReset::arrayReset']
	    ],
	    'src/ParserFunctions/ComplexArraySearch.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::getResult'],
	        'PhanTypeMismatchPropertyProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::arraySearch']
	    ],
	    'src/ParserFunctions/ComplexArraySearchArray.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::getResult'],
	        'PhanCommentParamOutOfOrder' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::arraySearchArray'],
	        'PhanImpossibleValueComparison' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::arraySearchArray'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySize.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::arraySize']
	    ],
	    'src/ParserFunctions/ComplexArraySlice.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::arraySlice', '\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySort.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arraySort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::getResult'],
	        'PhanRedundantCondition' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::asort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::krsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natcasesort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::rsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::shuffle', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::sort'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::asort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::keysort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::krsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::multisort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natcasesort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::rsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::shuffle', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::sort'],
	        'PhanUnextractableAnnotation' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort']
	    ],
	    'src/ParserFunctions/ComplexArrayUnique.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayUnset.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::getResult']
	    ],
	],
	// 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
	// (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];
