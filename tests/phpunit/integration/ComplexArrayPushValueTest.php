<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayPushValueTest extends ComplexArraysIntegrationTestCase {

	public static function providePushes(): array {
		return [
			'value is appended to a list' => [
				'a,b', 'example', 'c',
				"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
			'list value is appended as a sublist' => [
				'a,b', 'example', 'c,d',
				"<ul><li>a</li>\n<li>b</li>\n<li>2\n<ul><li>c</li>\n<li>d</li></ul></li></ul>"
			],
			'value is appended to the root of a multidimensional list' => [
				'[["a"],["b"]]', 'example', 'c',
				"<ul><li>0\n<ul><li>a</li></ul></li>\n<li>1\n<ul><li>b</li></ul></li>\n<li>c</li></ul>"
			],
			'value is appended to a sublist' => [
				'[["a"],["b"]]', 'example[0]', 'c',
				"<ul><li>0\n<ul><li>a</li>\n<li>c</li></ul></li>\n<li>1\n<ul><li>b</li></ul></li></ul>"
			],
			'list value is appended to the root of a multidimensional list' => [
				'[["a"],["b"]]', 'example', 'c,d',
				"<ul><li>0\n<ul><li>a</li></ul></li>\n<li>1\n<ul><li>b</li></ul></li>\n"
					. "<li>2\n<ul><li>c</li>\n<li>d</li></ul></li></ul>"
			],
			'list value is appended to a sublist' => [
				'[["a"],["b"]]', 'example[0]', 'c,d',
				"<ul><li>0\n<ul><li>a</li>\n<li>1\n<ul><li>c</li>\n<li>d</li></ul></li></ul></li>\n"
					. "<li>1\n<ul><li>b</li></ul></li></ul>"
			],
			'zero is a valid value' => [
				'a,b', 'example', '0',
				"<ul><li>a</li>\n<li>b</li>\n<li>0</li></ul>"
			],
			'scalar at the path is turned into a list' => [
				'a,b', 'example[0]', 'c',
				"<ul><li>0\n<ul><li>a</li>\n<li>c</li></ul></li>\n<li>b</li></ul>"
			],
		];
	}

	/**
	 * @dataProvider providePushes
	 */
	public function testPush( string $definition, string $reference, string $value, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarraypush:$reference|$value}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testPushWithNoparseKeepsMarkupUnexpanded(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>{{!}}</li></ul>",
			'{{#complexarraydefine:example|a,b}}'
				. '{{#complexarraypush:example|{{!}}|NO_IGNORE,NO_ARGS,NO_TEMPLATES,NO_TAGS}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public function testPushSubarrayWithNoparseKeepsMarkupUnexpanded(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>2\n<ul><li>{{!}}</li>\n<li>{{!}}</li></ul></li></ul>",
			'{{#complexarraydefine:example|a,b}}'
				. '{{#complexarraypush:example|{{!}},{{!}}|NO_IGNORE,NO_ARGS,NO_TEMPLATES,NO_TAGS}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public function testPushCreatesUndefinedArray(): void {
		$this->assertParsesTo(
			"<p>0: a\n</p>",
			'{{#complexarraypush:foobar|a}}{{#complexarrayprint:foobar}}'
		);
	}

	public function testPushProducesNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarraydefine:example|a,b}}{{#complexarraypush:example|c}}' );
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'Name must not be omitted', $this->parse( '{{#complexarraypush:|c}}' ) );
	}

	public function testMissingValueYieldsError(): void {
		$this->assertStringContainsString(
			'Value must not be omitted',
			$this->parse( '{{#complexarraydefine:example|a,b}}{{#complexarraypush:example|}}' )
		);
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarraypush:123|c}}' ) );
	}
}
