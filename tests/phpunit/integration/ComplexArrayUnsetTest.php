<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 * @covers \ComplexArrayUnset
 */
class ComplexArrayUnsetTest extends ComplexArraysIntegrationTestCase {

	public static function provideUnsets(): array {
		return [
			'list element is removed and list reindexed' => [
				'a,b,c', 'example[1]', "<ul><li>a</li>\n<li>c</li></ul>"
			],
			'last element is removed' => [
				'a,b,c', 'example[2]', "<ul><li>a</li>\n<li>b</li></ul>"
			],
			'sublist of a multidimensional list is removed and list reindexed' => [
				'[["a"], ["b"], ["c"]]',
				'example[0]',
				"<ul><li>0\n<ul><li>b</li></ul></li>\n<li>1\n<ul><li>c</li></ul></li></ul>"
			],
			'object key is removed' => [
				'(("foo": "x", "bar": "y"))', 'example[foo]', "<p>bar: y\n</p>"
			],
			'unsetting the only element of a sublist removes the dangling sublist' => [
				'["a", ["b"]]', 'example[1][0]', "<p>0: a\n</p>"
			],
			'non-existent index leaves array unchanged' => [
				'a,b,c', 'example[999]', "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
			'unsetting the entire array is not supported' => [
				'a,b,c', 'example', "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideUnsets
	 */
	public function testUnset( string $definition, string $reference, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayunset:$reference}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testUnsetProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayunset:example[1]}}"
		);
	}

	public function testUnsetOnUndefinedArrayYieldsNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarrayunset:foobzr[999]}}' );
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'Array key must not be omitted', $this->parse( '{{#complexarrayunset:}}' ) );
	}
}
