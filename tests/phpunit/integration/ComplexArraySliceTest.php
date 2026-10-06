<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 * @covers \ComplexArraySlice
 */
class ComplexArraySliceTest extends ComplexArraysIntegrationTestCase {

	public static function provideSlices(): array {
		return [
			'first element' => [ 'a,b,c', '0', '1', "<p>0: a\n</p>" ],
			'second element' => [ 'a,b,c', '1', '1', "<p>0: b\n</p>" ],
			'last element via negative offset' => [ 'a,b,c', '-1', '1', "<p>0: c\n</p>" ],
			'range from the middle' => [ 'a,b,c', '1', '2', "<ul><li>b</li>\n<li>c</li></ul>" ],
			'omitted length slices to the end' => [ 'a,b,c', '1', '', "<ul><li>b</li>\n<li>c</li></ul>" ],
			'length beyond the end is truncated' => [ 'a,b,c', '1', '100', "<ul><li>b</li>\n<li>c</li></ul>" ],
			'last two sublists of a multidimensional array' => [
				'[["a"], ["b"], ["c"]]', '-2', '2',
				"<ul><li>0\n<ul><li>b</li></ul></li>\n<li>1\n<ul><li>c</li></ul></li></ul>"
			],
			'offset beyond bounds yields empty array' => [ 'a,b', '1000', '10289', '' ],
			'negative length stops before the end' => [ 'a,b,c', '0', '-1', "<ul><li>a</li>\n<li>b</li></ul>" ],
			'negative length cutting everything yields empty array' => [ 'a,b', '1', '-100', '' ],
		];
	}

	/**
	 * @dataProvider provideSlices
	 */
	public function testSlice( string $definition, string $offset, string $length, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}"
				. "{{#complexarrayslice:example2|example|$offset|$length}}{{#complexarrayprint:example2}}"
		);
	}

	public function testSliceDoesNotModifySourceArray(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>",
			'{{#complexarraydefine:example|a,b,c}}{{#complexarrayslice:example2|example|1|1}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public function testSliceProducesNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:example|a,b,c}}{{#complexarrayslice:example2|example|0|1}}'
		);
	}

	public function testSliceOfUndefinedArrayYieldsNoOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarrayslice:example2|example12312|1|-100}}{{#complexarrayprint:example2}}'
		);
	}

	public function testMissingNewNameYieldsError(): void {
		$this->assertStringContainsString(
			'New array key must not be omitted',
			$this->parse( '{{#complexarrayslice:|example|0|1}}' )
		);
	}

	public function testMissingSourceNameYieldsError(): void {
		$this->assertStringContainsString(
			'Array key must not be omitted',
			$this->parse( '{{#complexarrayslice:example2||0|1}}' )
		);
	}
}
