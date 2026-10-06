<?php

namespace ComplexArrays\Tests\SMW;

use ComplexArrays\Hooks;
use MediaWiki\MediaWikiServices;
use SMW\Tests\Integration\JSONScript\JSONScriptTestCaseRunnerTest;

/**
 * Runs the JSON scripts in TestCases: pages are created, a query is run with
 * format=complexarray and the output of the page is asserted.
 *
 * @see https://github.com/SemanticMediaWiki/SemanticMediaWiki/tree/master/tests#write-integration-tests-using-json-script
 *
 * @covers \ComplexArrays\SMW\ComplexArrayPrinter
 *
 * @group ComplexArrays
 * @group SMWExtension
 * @group Database
 */
class JsonTestCaseScriptRunnerTest extends JSONScriptTestCaseRunnerTest {

	protected function setUp(): void {
		parent::setUp();

		// The runner of SMW before 7 removes all handlers of the parser hooks it knows,
		// including ours; register them again so the parser functions are available.
		$hooks = new Hooks();
		$container = MediaWikiServices::getInstance()->getHookContainer();
		$container->register( 'ParserFirstCallInit', [ $hooks, 'onParserFirstCallInit' ] );
		$container->register( 'ParserClearState', [ $hooks, 'onParserClearState' ] );
	}

	/**
	 * @return string
	 */
	protected function getTestCaseLocation(): string {
		return __DIR__ . '/TestCases';
	}
}
