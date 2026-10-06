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
	// PhanUndeclaredTypeThrowsType : 45+ occurrences
	// MediaWikiNoEmptyIfDefined : 30+ occurrences
	// PhanUndeclaredClassReference : 20+ occurrences
	// PhanTypeMismatchReturn : 15+ occurrences
	// PhanRedundantCondition : 8 occurrences
	// PhanTypeMismatchArgumentProbablyReal : 5 occurrences
	// PhanUndeclaredExtendedClass : 4 occurrences
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
	    'src/ComplexArray.php' => [
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ComplexArray::getArray']
	    ],
	    'src/ComplexArrayWrapper.php' => [
	        'PhanTypeMismatchPropertyProbablyReal' => ['\\ComplexArrays\\ComplexArrayWrapper::reset']
	    ],
	    'src/GlobalFunctions.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\GlobalFunctions::getValue'],
	        'MediaWikiNoIssetIfDefined' => ['\\ComplexArrays\\GlobalFunctions::getValue'],
	        'PhanCommentParamWithoutRealParam' => ['\\ComplexArrays\\GlobalFunctions::getArrayFromArrayName', '\\ComplexArrays\\GlobalFunctions::getSubarrayFromArrayName'],
	        'PhanNonClassMethodCall' => ['\\ComplexArrays\\GlobalFunctions::getSFHValue', '\\ComplexArrays\\GlobalFunctions::rawValue'],
	        'PhanTypeMismatchReturnProbablyReal' => ['\\ComplexArrays\\GlobalFunctions::getValue'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\GlobalFunctions::getArrayFromArrayName', '\\ComplexArrays\\GlobalFunctions::getArrayFromComplexArray', '\\ComplexArrays\\GlobalFunctions::getSubarrayFromArrayName', '\\ComplexArrays\\GlobalFunctions::getValue']
	    ],
	    'src/Hooks.php' => [
	        'PhanUndeclaredClassReference' => ['\\ComplexArrays\\Hooks']
	    ],
	    'src/ParserFunctions/ComplexArrayAddValue.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::arrayAddValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::set'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::arrayAddValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayAddValue::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayArrayMap.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayArrayMap::arrayArrayMap'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayArrayMap::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayArrayMap::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDefine.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::arrayDefine', '\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::arrayDefine', '\\ComplexArrays\\ParserFunctions\\ComplexArrayDefine::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDefinedArrays.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefinedArrays::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDefinedArrays::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayDiff.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDiff::arrayDiff'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayDiff::arrayDiff', '\\ComplexArrays\\ParserFunctions\\ComplexArrayDiff::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayDiff::pushArrays']
	    ],
	    'src/ParserFunctions/ComplexArrayExtract.php' => [
	        'PhanCommentParamWithoutRealParam' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract', '\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::arrayExtract', '\\ComplexArrays\\ParserFunctions\\ComplexArrayExtract::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayMap.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::arrayMap'],
	        'PhanPluginUnreachableCode' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::replaceCallback'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getResult'],
	        'PhanTypeMismatchArgumentInternal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::iterate'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::replaceCallback'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::arrayMap', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::getValueFromMatch', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMap::replaceCallback']
	    ],
	    'src/ParserFunctions/ComplexArrayMapTemplate.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::getResult'],
	        'PhanTypeSuspiciousStringExpression' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::map'],
	        'PhanUndeclaredExtendedClass' => ['src/ParserFunctions/ComplexArrayMapTemplate.php'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::arrayMapTemplate', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMapTemplate::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayMerge.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMerge::arrayMerge'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayMerge::arrayMerge', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMerge::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayMerge::iterate']
	    ],
	    'src/ParserFunctions/ComplexArrayParent.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayParent::getResult'],
	        'PhanUndeclaredExtendedClass' => ['src/ParserFunctions/ComplexArrayParent.php']
	    ],
	    'src/ParserFunctions/ComplexArrayPrint.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::arrayPrint', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::getResult'],
	        'PhanPluginUnreachableCode' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::applyOptions'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::getResult'],
	        'PhanUndeclaredExtendedClass' => ['src/ParserFunctions/ComplexArrayPrint.php'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::arrayPrint', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPrint::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayPushArray.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushArray::arrayPush'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushArray::arrayPush', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPushArray::getResult', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPushArray::iterate']
	    ],
	    'src/ParserFunctions/ComplexArrayPushValue.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::arrayPushValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::getResult'],
	        'PhanTypeMismatchArgumentProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::arrayPushValue', '\\ComplexArrays\\ParserFunctions\\ComplexArrayPushValue::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayReset.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayReset::arrayReset']
	    ],
	    'src/ParserFunctions/ComplexArraySearch.php' => [
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::getResult'],
	        'PhanTypeMismatchPropertyProbablyReal' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::arraySearch'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::arraySearch', '\\ComplexArrays\\ParserFunctions\\ComplexArraySearch::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySearchArray.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::getResult'],
	        'PhanCommentParamOutOfOrder' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::arraySearchArray'],
	        'PhanImpossibleValueComparison' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::arraySearchArray'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::arraySearchArray', '\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::findValues', '\\ComplexArrays\\ParserFunctions\\ComplexArraySearchArray::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySize.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::arraySize'],
	        'PhanUndeclaredExtendedClass' => ['src/ParserFunctions/ComplexArraySize.php'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySize::arraySize', '\\ComplexArrays\\ParserFunctions\\ComplexArraySize::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySlice.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::arraySlice', '\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::arraySlice', '\\ComplexArrays\\ParserFunctions\\ComplexArraySlice::getResult']
	    ],
	    'src/ParserFunctions/ComplexArraySort.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arraySort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::getResult'],
	        'PhanRedundantCondition' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::asort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::krsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natcasesort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::rsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::shuffle', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::sort'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::asort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::keysort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::krsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::multisort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natcasesort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::natsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::rsort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::shuffle', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::sort'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort::arraySort', '\\ComplexArrays\\ParserFunctions\\ComplexArraySort::getResult'],
	        'PhanUnextractableAnnotation' => ['\\ComplexArrays\\ParserFunctions\\ComplexArraySort']
	    ],
	    'src/ParserFunctions/ComplexArrayUnique.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult'],
	        'PhanTypeMismatchReturn' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::arrayUnique', '\\ComplexArrays\\ParserFunctions\\ComplexArrayUnique::getResult']
	    ],
	    'src/ParserFunctions/ComplexArrayUnset.php' => [
	        'MediaWikiNoEmptyIfDefined' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::getResult'],
	        'PhanTypeMismatchArgument' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::getResult'],
	        'PhanUndeclaredTypeThrowsType' => ['\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::arrayUnset', '\\ComplexArrays\\ParserFunctions\\ComplexArrayUnset::getResult']
	    ],
	],
	// 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
	// (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];
