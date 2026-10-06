<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArraySearchTest extends ComplexArraysIntegrationTestCase {

	public static function provideSearches(): array {
		return [
			'one-dimensional array' => [ 'a,b,c,d,e,f,g', 'd', 'example[3]' ],
			'two-dimensional list' => [ '[["a"], ["b"], ["c"]]', 'c', 'example[2][0]' ],
			'multidimensional associative array' => [ '(("a": (("b": "c"))))', 'c', 'example[a][b]' ],
		];
	}

	/**
	 * @dataProvider provideSearches
	 */
	public function testSearch( string $definition, string $needle, string $expected ): void {
		$this->assertParsesToText(
			$expected,
			"{{#complexarraydefine:example|$definition}}{{#complexarraysearch:example|$needle}}"
		);
	}

	public function testNotFoundYieldsEmptyOutput(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:example|a,b,c}}{{#complexarraysearch:example|d}}'
		);
	}

	public function testUndefinedArrayYieldsNothing(): void {
		$this->assertParsesTo( '', '{{#complexarraysearch:example1234|a}}' );
	}
}
