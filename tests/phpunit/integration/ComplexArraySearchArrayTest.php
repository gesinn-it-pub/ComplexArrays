<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArraySearchArrayTest extends ComplexArraysIntegrationTestCase {

	public static function provideSearches(): array {
		return [
			'one-dimensional array' => [
				'a,b,c,d,e,f,d', "<ul><li>example[3]</li>\n<li>example[6]</li></ul>"
			],
			'two-dimensional list' => [
				'[["a"], ["a"], ["a"]]', "<ul><li>example[0][0]</li>\n<li>example[1][0]</li>\n<li>example[2][0]</li></ul>"
			],
		];
	}

	/**
	 * @dataProvider provideSearches
	 */
	public function testSearchArray( string $definition, string $expectedHtml ): void {
		$needle = str_contains( $definition, 'd' ) ? 'd' : 'a';
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarraysearcharray:example2|example|$needle}}"
				. "{{#complexarrayprint:example2}}"
		);
	}

	public function testUndefinedArrayYieldsNothing(): void {
		$this->assertParsesTo( '', '{{#complexarraysearcharray:example1234|ejflsf|a}}' );
	}

	public static function provideInvalidArguments(): array {
		return [
			'missing new name' => [ '' ],
			'invalid new name' => [ '123|example|a' ],
			'missing array name' => [ 'example2' ],
			'missing value' => [ 'example2|example' ],
		];
	}

	/**
	 * @dataProvider provideInvalidArguments
	 */
	public function testInvalidArgumentsYieldError( string $arguments ): void {
		$this->assertStringContainsString(
			'error',
			$this->parse( "{{#complexarraydefine:example|a,b}}{{#complexarraysearcharray:$arguments}}" )
		);
	}
}
