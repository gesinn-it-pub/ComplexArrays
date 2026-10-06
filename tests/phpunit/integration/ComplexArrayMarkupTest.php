<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * The markup writes JSON objects with "((" and "))" instead of braces; text inside
 * of strings must never be converted.
 *
 * @group Database
 */
class ComplexArrayMarkupTest extends ComplexArraysIntegrationTestCase {

	public static function provideMarkup(): array {
		return [
			'object' => [ '(("k":"v"))' ],
			'nested objects' => [ '(("a":(("b":["1","2"]))))' ],
			'list of objects' => [ '[(("a":"1")),(("b":"2"))]' ],
			'braces inside a string' => [ '(("k":"a{b}c"))' ],
			'doubled parentheses inside a string' => [ '(("k":"x((y))z"))' ],
			'quote and brace inside a string' => [ '(("k":"say \\"{hi}\\" now"))' ],
			'brace in a key' => [ '(("{k}":"v"))' ],
			'string ending with a backslash' => [ '(("k":"a\\\\","l":"{"))' ],
		];
	}

	/**
	 * @dataProvider provideMarkup
	 */
	public function testMarkupSurvivesDefineAndPrint( string $markup ): void {
		$this->assertParsesToText(
			$markup,
			"{{#complexarraydefine:example|$markup}}{{#complexarrayprint:example|markup}}"
		);
	}
}
