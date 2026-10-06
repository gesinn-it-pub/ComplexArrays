<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 * @covers \ComplexArrayDiff
 */
class ComplexArrayDiffTest extends ComplexArraysIntegrationTestCase {

	public static function provideDiffs(): array {
		return [
			'distinct lists keep all elements of the first' => [
				'a,b,c', 'd,e,f', "<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>"
			],
			'identical lists yield an empty array' => [ 'a,b,c', 'a,b,c', '' ],
			'only differing positions remain' => [ 'a,b,c', 'a,x,c', '<p>1: b' . "\n</p>" ],
			'elements missing from the second list remain' => [ 'a,b,c', 'a', "<ul><li>b</li>\n<li>c</li></ul>" ],
		];
	}

	/**
	 * @dataProvider provideDiffs
	 */
	public function testDiff( string $first, string $second, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:foo|$first}}{{#complexarraydefine:bar|$second}}"
				. "{{#complexarraydiff:example|foo|bar}}{{#complexarrayprint:example}}"
		);
	}

	public function testDiffProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:foo|a,b}}{{#complexarraydefine:bar|c}}{{#complexarraydiff:example|foo|bar}}'
		);
	}

	public function testMultidimensionalArraysYieldError(): void {
		$this->assertStringContainsString(
			'ComplexArrayDiff can only deal with one-dimensional array',
			$this->parse(
				'{{#complexarraydefine:foo|[["a"],["b"],["c"]]}}{{#complexarraydefine:bar|[["d"],["e"],["f"]]}}'
					. '{{#complexarraydiff:example|foo|bar}}'
			)
		);
	}

	public function testSingleDefinedArrayYieldsError(): void {
		$this->assertStringContainsString(
			'You must provide at least two arrays',
			$this->parse( '{{#complexarraydefine:foo|a,b,c}}{{#complexarraydiff:example|foo|missing}}' )
		);
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( '{{#complexarraydefine:foo|a}}{{#complexarraydefine:bar|b}}{{#complexarraydiff:123|foo|bar}}' )
		);
	}
}
