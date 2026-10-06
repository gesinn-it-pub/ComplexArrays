<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayExtractTest extends ComplexArraysIntegrationTestCase {

	public static function provideExtractions(): array {
		return [
			'item of a list' => [ 'a,b,c', 'example[0]', '<p>0: a' . "\n</p>" ],
			'array of a two-dimensional list' => [ '[["a"],["b"],["c"]]', 'example[0]', '<p>0: a' . "\n</p>" ],
			'array with multiple items' => [
				'[["a", "b"],["b"],["c"]]', 'example[0]', "<ul><li>a</li>\n<li>b</li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideExtractions
	 */
	public function testExtract( string $definition, string $reference, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarrayextract:example2|$reference}}"
				. "{{#complexarrayprint:example2}}"
		);
	}

	public function testExtractFromUndefinedArrayPrintsNothing(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarrayextract:aaa|example123[0]}}{{#complexarrayprint:aaa}}'
		);
	}

	public function testMissingNewNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarrayextract:}}' ) );
	}

	public function testInvalidNewNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarrayextract:123|example[0]}}' ) );
	}

	public function testMissingReferenceYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarrayextract:example2}}' ) );
	}

	public function testReferenceWithoutKeysYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( '{{#complexarraydefine:example|a,b}}{{#complexarrayextract:example2|example}}' )
		);
	}
}
