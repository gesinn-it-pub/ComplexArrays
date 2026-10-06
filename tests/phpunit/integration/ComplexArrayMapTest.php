<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayMapTest extends ComplexArraysIntegrationTestCase {

	public static function provideMappings(): array {
		return [
			'one-dimensional list' => [
				'a,b,c', '@@@|Hello, @@@!|<br/>', '<p>Hello, a!<br />Hello, b!<br />Hello, c!' . "\n</p>"
			],
			'sub-arrays cannot be printed as a value' => [
				'[["a", "b"],["c", "d"],["e", "f"]]', '@@@|Hello, @@@!|<br/>',
				'<p>Hello,&#160;!<br />Hello,&#160;!<br />Hello,&#160;!' . "\n</p>"
			],
			'first element of sub-arrays' => [
				'[["a", "b"],["c", "d"],["e", "f"]]', '@@@|Hello, @@@[0]!|<br/>',
				'<p>Hello, a!<br />Hello, c!<br />Hello, e!' . "\n</p>"
			],
			'uniform list displaying the mapping key' => [
				'[["a", "b"],["c", "d"],["e", "f"]]', '@@@|Hello, @@@!|<br/>|true',
				'<p>Hello, @@@!<br />Hello, @@@!<br />Hello, @@@!' . "\n</p>"
			],
			'non-uniform list displaying the mapping key' => [
				'[["a", "b"],["d"],["e", "f"]]', '@@@|Hello, @@@[1]!|<br/>|true',
				'<p>Hello, b!<br />Hello, @@@[1]!<br />Hello, f!' . "\n</p>"
			],
			'non-uniform list without displaying the mapping key' => [
				'[["a", "b"],["d"],["e", "f"]]', '@@@|Hello, @@@[1]!|<br/>',
				'<p>Hello, b!<br />Hello,&#160;!<br />Hello, f!' . "\n</p>"
			],
		];
	}

	/**
	 * @dataProvider provideMappings
	 */
	public function testMap( string $definition, string $arguments, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarraydefine:example|$definition}}{{#complexarraymap:example|$arguments}}"
		);
	}
}
