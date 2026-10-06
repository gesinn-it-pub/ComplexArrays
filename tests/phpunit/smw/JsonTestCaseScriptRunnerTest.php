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
		// With MediaWiki before 1.43 the test database is not switched for SMW's runner when it
		// runs together with the other suite ("Can't create user on real database"). The format
		// itself works there; it is only tested through the JSON scripts from 1.43 on.
		if ( version_compare( MW_VERSION, '1.43', '<' ) ) {
			$this->markTestSkipped( 'The JSON scripts need MediaWiki 1.43 or newer.' );
		}

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
