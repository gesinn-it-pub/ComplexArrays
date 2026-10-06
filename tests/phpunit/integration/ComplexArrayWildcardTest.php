<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayWildcardTest extends ComplexArraysIntegrationTestCase {

	public function testWildcardPrintsKeyOfAllSubArrays(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>",
			'{{#complexarraydefine:example|[(("a": "a")),(("a": "b")),(("a": "c"))]}}'
				. '{{#complexarrayprint:example[*][a]}}'
		);
	}

	public function testSequentialWildcards(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>",
			'{{#complexarraydefine:example|[(("a": "a")),(("a": "b")),(("a": "c"))]}}'
				. '{{#complexarrayprint:example[*][*][a]}}'
		);
	}

	public function testWildcardWithNonExistentKeyPrintsNothing(): void {
		$this->assertParsesTo(
			'',
			'{{#complexarraydefine:example|[(("a": "a")),(("a": "b")),(("a": "c"))]}}'
				. '{{#complexarrayprint:example[*][b]}}'
		);
	}
}
