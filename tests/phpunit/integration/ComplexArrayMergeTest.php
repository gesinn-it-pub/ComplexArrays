<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayMergeTest extends ComplexArraysIntegrationTestCase {

	public static function provideMerges(): array {
		return [
			'two lists' => [
				[ 'left' => 'a,b,c', 'right' => 'd,e,f' ], 'left|right',
				"<ul><li>a</li>\n<li>b</li>\n<li>c</li>\n<li>d</li>\n<li>e</li>\n<li>f</li></ul>"
			],
			'two one-item lists' => [
				[ 'a' => 'a', 'b' => 'b' ], 'a|b',
				"<ul><li>a</li>\n<li>b</li></ul>"
			],
			'many lists' => [
				[ 'a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f' ], 'a|b|c|d|e|f',
				"<ul><li>a</li>\n<li>b</li>\n<li>c</li>\n<li>d</li>\n<li>e</li>\n<li>f</li></ul>"
			],
			'two json lists' => [
				[ 'left' => '["a","b"]', 'right' => '["c","d"]' ], 'left|right',
				"<ul><li>a</li>\n<li>b</li>\n<li>c</li>\n<li>d</li></ul>"
			],
			'list and json list' => [
				[ 'left' => 'a', 'right' => '["c","d"]' ], 'left|right',
				"<ul><li>a</li>\n<li>c</li>\n<li>d</li></ul>"
			],
			'objects with the same key are overwritten by the later one' => [
				[ 'left' => '(("a": "1", "b": "2"))', 'right' => '(("b": "3", "c": "4"))' ], 'left|right',
				"<ul><li>a: 1</li>\n<li>b: 3</li>\n<li>c: 4</li></ul>"
			],
			'undefined arrays are skipped' => [
				[ 'left' => 'a,b' ], 'left|missing',
				"<ul><li>a</li>\n<li>b</li></ul>"
			],
			'only undefined arrays yields empty result' => [
				[], 'aaaa|bbbb', ''
			],
		];
	}

	/**
	 * @dataProvider provideMerges
	 */
	public function testMerge( array $definitions, string $sources, string $expectedHtml ): void {
		$wikitext = '';
		foreach ( $definitions as $name => $definition ) {
			$wikitext .= "{{#complexarraydefine:$name|$definition}}";
		}

		$this->assertParsesTo(
			$expectedHtml,
			$wikitext . "{{#complexarraymerge:example|$sources}}{{#complexarrayprint:example}}"
		);
	}

	public function testRecursiveMergeCombinesValuesOfSameKey(): void {
		$this->assertParsesTo(
			"<ul><li>a\n<ul><li>1</li>\n<li>3</li></ul></li></ul>",
			'{{#complexarraydefine:left|(("a": "1"))}}{{#complexarraydefine:right|(("a": "3"))}}'
				. '{{#complexarraymerge:example|left|right|recursive}}{{#complexarrayprint:example}}'
		);
	}

	public function testMergeProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:a|a}}{{#complexarraydefine:b|b}}{{#complexarraymerge:example|a|b}}'
		);
	}

	public function testSingleSourceArrayYieldsError(): void {
		$this->assertStringContainsString(
			'You must provide at least two arrays',
			$this->parse( '{{#complexarraydefine:foo|a,b,c}}{{#complexarraymerge:example|foo}}' )
		);
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( '{{#complexarraydefine:a|a}}{{#complexarraydefine:b|b}}{{#complexarraymerge:123|a|b}}' )
		);
	}
}
