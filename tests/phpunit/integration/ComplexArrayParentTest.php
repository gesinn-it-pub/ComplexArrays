<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayParentTest extends ComplexArraysIntegrationTestCase {

	public static function provideReferences(): array {
		return [
			'drops the last key' => [ 'foobar[a][b][c]', 'foobar[a][b]' ],
			'without keys the reference is unchanged' => [ 'foobar', 'foobar' ],
		];
	}

	/**
	 * @dataProvider provideReferences
	 */
	public function testParent( string $reference, string $expected ): void {
		$this->assertParsesToText( $expected, "{{#complexarrayparent: $reference}}" );
	}

	public function testEmptyReferenceYieldsError(): void {
		$this->assertParsesTo(
			'<p><span class="error">Key must not be omitted</span>' . "\n</p>",
			'{{#complexarrayparent:}}'
		);
	}
}
