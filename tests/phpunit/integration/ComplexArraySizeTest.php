<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 * @covers \ComplexArraySize
 */
class ComplexArraySizeTest extends ComplexArraysIntegrationTestCase {

	private const TWO_DIMENSIONAL =
		"{{#complexarraydefine:example|[[\"a\", \"b\", \"c\"], [\"d\", \"e\", \"f\"]]}}\n";

	public function testOneDimensionalArrayCountsAllElements(): void {
		$this->assertParsesToText(
			'4',
			"{{#complexarraydefine:example|a,b,c,d}}\n{{#complexarraysize:example}}"
		);
	}

	public function testNestedArrayCountsRecursively(): void {
		// 2 sub-arrays + 6 values
		$this->assertParsesToText(
			'8',
			self::TWO_DIMENSIONAL . "{{#complexarraysize:example}}"
		);
	}

	public function testTopOptionCountsOnlyTopLevelElements(): void {
		$this->assertParsesToText(
			'2',
			self::TWO_DIMENSIONAL . "{{#complexarraysize:example|top}}"
		);
	}

	public function testUndefinedArrayYieldsNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarraysize:foobar}}' );
	}

	public function testUndefinedArrayWithTopOptionYieldsNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarraysize:foobar|top}}' );
	}
}
