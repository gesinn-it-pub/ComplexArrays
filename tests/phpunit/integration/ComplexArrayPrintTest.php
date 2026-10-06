<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 * @covers \ComplexArrayPrint
 */
class ComplexArrayPrintTest extends ComplexArraysIntegrationTestCase {

	public static function provideIndexedPrints(): array {
		return [
			'list element by index' => [ 'a,b,c', 'example[0]', 'a' ],
			'last list element' => [ 'a,b,c', 'example[2]', 'c' ],
			'nested element by key and index' => [ '(("foo": ["bar"]))', 'example[foo][0]', 'bar' ],
			'nested element by numeric keys' => [ '(("0": ["bar"]))', 'example[0][0]', 'bar' ],
		];
	}

	/**
	 * @dataProvider provideIndexedPrints
	 */
	public function testPrintsElementAtIndex( string $definition, string $reference, string $expected ): void {
		$this->assertParsesToText(
			$expected,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayprint:$reference}}"
		);
	}

	public function testPrintsListAsBulletList(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>",
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayprint:example}}"
		);
	}

	public function testMarkupOptionPrintsJson(): void {
		$this->assertParsesToText(
			'["a","b","c"]',
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayprint:example|markup}}"
		);
	}

	public function testAmbiguousNumericKeysPrintNothing(): void {
		// "0" and 0 collide as keys, so the reference cannot be resolved.
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example|((\"0\": [\"bar\"], 0: [\"foo\"]))}}"
				. "{{#complexarrayprint:example[0][0]}}"
		);
	}

	public function testUndefinedArrayPrintsNothing(): void {
		$this->assertParsesTo( '', '{{#complexarrayprint:foobar}}' );
	}

	public function testUndefinedIndexPrintsNothing(): void {
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayprint:example[999]}}"
		);
	}
}
