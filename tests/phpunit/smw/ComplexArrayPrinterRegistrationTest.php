<?php

namespace ComplexArrays\Tests\SMW;

use SMW\Tests\QueryPrinterRegistryTestCase;

/**
 * @covers \ComplexArrays\SMW\ComplexArrayPrinter
 * @covers \ComplexArrays\SMW\Hooks
 *
 * @group ComplexArrays
 * @group SMWExtension
 * @group ResultPrinters
 */
class ComplexArrayPrinterRegistrationTest extends QueryPrinterRegistryTestCase {

	/**
	 * @return string[]
	 */
	public function getFormats() {
		return [ 'complexarray' ];
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return 'ComplexArrays\SMW\ComplexArrayPrinter';
	}

	public function testFormatIsRegistered(): void {
		$this->assertSame(
			'ComplexArrays\SMW\ComplexArrayPrinter',
			$GLOBALS['smwgResultFormats']['complexarray']
		);
	}
}
