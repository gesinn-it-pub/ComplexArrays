<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayDefinedArraysTest extends ComplexArraysIntegrationTestCase {

	public function testListsNamesOfDefinedArrays(): void {
		$this->assertParsesTo(
			"<ul><li>foo</li>\n<li>bar</li></ul>",
			'{{#complexarraydefine:foo|a}}{{#complexarraydefine:bar|b}}'
				. '{{#complexarraydefinedarrays:names}}{{#complexarrayprint:names}}'
		);
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarraydefinedarrays:}}' ) );
	}

	public function testInvalidNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarraydefinedarrays:123}}' ) );
	}
}
