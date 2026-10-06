<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayMapTemplateTest extends ComplexArraysIntegrationTestCase {

	private const LINK = '<a href="/index.php?title=Template:Example&amp;action=edit&amp;redlink=1" class="new" '
		. 'title="Template:Example (page does not exist)">Template:Example</a>';

	public static function provideDefinitions(): array {
		return [
			'list is one template call' => [ 'a,b,c', 1 ],
			'multidimensional array is one call per element' => [ '[["a"],["b"],["c"]]', 3 ],
			'nested multidimensional array' => [ '[["a", ["b"]],["b"],["c"]]', 3 ],
			'associative array is one template call' => [ '(("a": "b", "c": "d"))', 1 ],
		];
	}

	/**
	 * @dataProvider provideDefinitions
	 */
	public function testMapsArrayToTemplate( string $definition, int $calls ): void {
		$this->assertParsesTo(
			'<p>' . str_repeat( self::LINK, $calls ) . "\n</p>",
			"{{#complexarraydefine:example|$definition}}{{#complexarraymaptemplate:example|Example}}"
		);
	}

	public function testUndefinedArrayYieldsNothing(): void {
		$this->assertParsesTo( '', '{{#complexarraymaptemplate:boofar|Example}}' );
	}
}
