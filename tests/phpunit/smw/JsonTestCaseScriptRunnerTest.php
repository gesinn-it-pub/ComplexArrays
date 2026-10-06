<?php

namespace ComplexArrays\Tests\SMW;

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

	/**
	 * @return string
	 */
	protected function getTestCaseLocation(): string {
		return __DIR__ . '/TestCases';
	}
}
