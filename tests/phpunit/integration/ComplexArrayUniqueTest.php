<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayUniqueTest extends ComplexArraysIntegrationTestCase {

	public static function provideUniques(): array {
		return [
			'duplicate values are removed' => [
				'a,b,c,c', "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
			'list without duplicates is unchanged' => [
				'a,b,c', "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
			'first of several duplicates is kept' => [
				'c,a,c,b,a', "<ul><li>c</li>\n<li>a</li>\n<li>b</li></ul>"
			],
			'duplicate subarrays are removed, original keys kept' => [
				'[["a", "b"],["a", "b"],["a","c"]]',
				"<ul><li>0\n<ul><li>a</li>\n<li>b</li></ul></li>\n"
					. "<li>2\n<ul><li>a</li>\n<li>c</li></ul></li></ul>"
			],
			'duplicates inside a subarray are not touched' => [
				'[["a", "a"],["a", "b"],["a","c"]]',
				"<ul><li>0\n<ul><li>a</li>\n<li>a</li></ul></li>\n"
					. "<li>1\n<ul><li>a</li>\n<li>b</li></ul></li>\n"
					. "<li>2\n<ul><li>a</li>\n<li>c</li></ul></li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideUniques
	 */
	public function testUnique( string $definition, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayunique:example}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testUniqueProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example|a,b,c,c}}{{#complexarrayunique:example}}"
		);
	}

	public function testUniqueOnUndefinedArrayYieldsNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarrayunique:foobar}}' );
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'Array key must not be omitted', $this->parse( '{{#complexarrayunique:}}' ) );
	}
}
