<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayPushArrayTest extends ComplexArraysIntegrationTestCase {

	public static function providePushArrays(): array {
		return [
			'two lists' => [
				[ 'foo' => 'a,b,c', 'bar' => 'd,e,f' ], 'foo|bar',
				"<ul><li>0\n<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul></li>\n"
					. "<li>1\n<ul><li>d</li>\n<li>e</li>\n<li>f</li></ul></li></ul>"
			],
			'three lists' => [
				[ 'a' => 'a', 'b' => 'b', 'c' => 'c' ], 'a|b|c',
				"<ul><li>0\n<ul><li>a</li></ul></li>\n<li>1\n<ul><li>b</li></ul></li>\n"
					. "<li>2\n<ul><li>c</li></ul></li></ul>"
			],
			'undefined arrays are skipped' => [
				[ 'aaa' => 'a,b,c' ], 'aaa|bbb',
				"<ul><li>0\n<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul></li></ul>"
			],
			'only undefined arrays yields empty result' => [
				[], 'ccc|ddd', ''
			],
		];
	}

	/**
	 * @dataProvider providePushArrays
	 */
	public function testPushArray( array $definitions, string $sources, string $expectedHtml ): void {
		$wikitext = '';
		foreach ( $definitions as $name => $definition ) {
			$wikitext .= "{{#complexarraydefine:$name|$definition}}";
		}

		$this->assertParsesTo(
			$expectedHtml,
			$wikitext . "{{#complexarraypusharray:example|$sources}}{{#complexarrayprint:example}}"
		);
	}

	public function testPushArrayProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:foo|a}}{{#complexarraydefine:bar|b}}{{#complexarraypusharray:example|foo|bar}}'
		);
	}

	public function testSingleSourceArrayYieldsError(): void {
		$this->assertStringContainsString(
			'You must provide at least two arrays',
			$this->parse( '{{#complexarraydefine:foo|a,b,c}}{{#complexarraypusharray:example|foo}}' )
		);
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( '{{#complexarraydefine:foo|a}}{{#complexarraydefine:bar|b}}'
				. '{{#complexarraypusharray:123|foo|bar}}' )
		);
	}
}
