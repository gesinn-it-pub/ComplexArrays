<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayResetTest extends ComplexArraysIntegrationTestCase {

	public function testResetRemovesNamedArray(): void {
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayreset:example}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testArrayIsPrintableBeforeReset(): void {
		$this->assertParsesTo(
			"<ul><li>a</li>\n<li>b</li>\n<li>c</li></ul>",
			"{{#complexarraydefine:example|a,b,c}}{{#complexarrayprint:example}}"
				. "{{#complexarrayreset:example}}"
		);
	}

	public function testResetOfOneArrayKeepsOthers(): void {
		$this->assertParsesToText(
			'd',
			"{{#complexarraydefine:example1|a,b,c}}{{#complexarraydefine:example2|d,e,f}}"
				. "{{#complexarrayreset:example1}}{{#complexarrayprint:example2[0]}}"
		);
	}

	public function testResetWithoutNameRemovesAllArrays(): void {
		$this->assertParsesTo(
			'',
			"{{#complexarraydefine:example1|a,b,c}}{{#complexarraydefine:example2|d,e,f}}"
				. "{{#complexarrayreset:}}{{#complexarraysize:example1}}{{#complexarraysize:example2}}"
		);
	}

	public function testResetOfUndefinedArrayYieldsNoOutput(): void {
		$this->assertParsesTo( '', '{{#complexarrayreset:example123}}' );
	}
}
