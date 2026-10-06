<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayAddValueTest extends ComplexArraysIntegrationTestCase {

	public static function provideAddValues(): array {
		return [
			'value is added at a new index of a list' => [
				'a,b', 'example[2]', 'c',
				"<ul><li>a</li>\n<li>b</li>\n<li>2\n<ul><li>c</li></ul></li></ul>"
			],
			'json list value is added as a sublist' => [
				'a,b,c', 'example[2]', '["c", "d"]',
				"<ul><li>a</li>\n<li>b</li>\n<li>2\n<ul><li>c</li>\n<li>d</li></ul></li></ul>"
			],
			'single-item json list replaces the existing value' => [
				'a,b,c', 'example[2]', '["c"]',
				"<ul><li>a</li>\n<li>b</li>\n<li>2\n<ul><li>c</li></ul></li></ul>"
			],
			'existing subarray is replaced' => [
				'(("a": ["a", "b"]))', 'example[a]', '["c", "d"]',
				"<ul><li>a\n<ul><li>c</li>\n<li>d</li></ul></li></ul>"
			],
			'new key is added to an object' => [
				'(("a": "x"))', 'example[b]', 'y',
				"<ul><li>a: x</li>\n<li>b\n<ul><li>y</li></ul></li></ul>"
			],
			'nested path is created' => [
				'a,b', 'example[5][x]', 'c',
				"<ul><li>a</li>\n<li>b</li>\n<li>5\n<ul><li>x\n<ul><li>c</li></ul></li></ul></li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideAddValues
	 */
	public function testAddValue( string $definition, string $reference, string $value, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayaddvalue:$reference|$value}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testAddValueProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:example|a,b}}{{#complexarrayaddvalue:example[2]|c}}'
		);
	}

	public function testAddValueToUndefinedArrayDoesNotCreateIt(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarrayaddvalue:foobar[a]|["b"]}}{{#complexarrayprint:foobar}}'
		);
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'Name must not be omitted', $this->parse( '{{#complexarrayaddvalue:|c}}' ) );
	}

	public function testMissingValueYieldsError(): void {
		$this->assertStringContainsString(
			'Value must not be omitted',
			$this->parse( '{{#complexarraydefine:example|a,b}}{{#complexarrayaddvalue:example[2]|}}' )
		);
	}

	public function testNameWithoutIndexYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( '{{#complexarraydefine:example|a,b}}{{#complexarrayaddvalue:example|c}}' )
		);
	}
}
