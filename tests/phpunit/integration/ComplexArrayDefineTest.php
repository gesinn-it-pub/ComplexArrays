<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * Definitions are observed through #complexarrayprint.
 *
 * @group Database
 * @covers \ComplexArrayDefine
 */
class ComplexArrayDefineTest extends ComplexArraysIntegrationTestCase {

	public static function provideDefinitions(): array {
		$list = "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>";

		return [
			'comma separated list' => [ 'a, b, c', $list ],
			'list with line breaks' => [ "a,\nb,\nc\n", $list ],
			'custom delimiter' => [ 'a;b;c|;', $list ],
			'custom delimiter with trailing delimiter keeps empty element' => [
				'a;b;c;|;',
				"<ul><li>a</li>\n<li>b</li>\n<li>c</li>\n<li></li></ul>"
			],
			'object with one key' => [ '(("foo": "bar"))', "<p>foo: bar\n</p>" ],
			'object with several keys' => [
				'(("foo": "bar", "boo": "far"))',
				"<ul><li>foo: bar</li>\n<li>boo: far</li></ul>"
			],
			'object with one nested list' => [
				'(("foo":["a", "b", "c"]))',
				"<ul><li>foo\n<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul></li></ul>"
			],
			'object with several nested lists' => [
				'(("foo":["a", "b", "c"],"bar":["d","e","f"]))',
				"<ul><li>foo\n<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul></li>\n"
					. "<li>bar\n<ul><li>d</li>\n<li>e</li>\n<li>f</li></ul></li></ul>"
			],
			'JSON list with line break' => [ "[\"foo\",\n\"bar\"]", "<ul><li>foo</li>\n<li>bar</li></ul>" ],
			'object with line break' => [ "((\"foo\":\n\"bar\"))", "<p>foo: bar\n</p>" ],
			'template is expanded' => [ 'a,b,{{!}}|,', "<ul><li>a</li>\n<li>b</li>\n<li>|</li></ul>" ],
			'NO_TEMPLATES keeps template unexpanded' => [
				'a,b,{{test}}|,|NO_IGNORE,NO_ARGS,NO_TEMPLATES,NO_TAGS',
				"<ul><li>a</li>\n<li>b</li>\n<li>{{test}}</li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideDefinitions
	 */
	public function testDefinedArrayIsPrinted( string $definition, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayprint:example}}"
		);
	}

	public function testDefineProducesNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarraydefine:example|a,b,c}}' );
	}

	public function testRedefinitionReplacesArray(): void {
		$this->assertParsesToText(
			'x',
			"{{#complexarraydefine:example|a,b,c}}{{#complexarraydefine:example|x}}"
				. "{{#complexarrayprint:example[0]}}"
		);
	}

	public function testEmptyDefinitionCreatesEmptyArray(): void {
		$this->assertParsesToText(
			'0',
			"{{#complexarraydefine:example}}{{#complexarraysize:example}}"
		);
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString(
			'Name must not be omitted',
			$this->parse( '{{#complexarraydefine:}}' )
		);
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString(
			'This name is invalid',
			$this->parse( '{{#complexarraydefine:in[valid|a,b}}' )
		);
	}
}
