<?php

namespace ComplexArrays\SMW;

/**
 * Hook handlers for the integration with Semantic MediaWiki. They are only run
 * if Semantic MediaWiki is installed, because the hook is fired by SMW.
 *
 * @license GPL-2.0-or-later
 */
class Hooks {

	// The hook name determines the method name.
	// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

	/**
	 * Registers the "complexarray" result format.
	 *
	 * @param array &$vars Configuration variables of SMW
	 * @return bool
	 */
	public function onSMW__Setup__AfterInitializationComplete( &$vars ) {
		$vars['smwgResultFormats']['complexarray'] = ComplexArrayPrinter::class;

		return true;
	}

	// phpcs:enable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
}
