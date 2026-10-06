<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayArrayMapTest extends ComplexArraysIntegrationTestCase {

	public static function provideMappings(): array {
		return [
			'items joined by delimiter' => [
				'a,b,c|,|####|Hello, ####!|<br/>', '<p>Hello, a!<br />Hello, b!<br />Hello, c!' . "\n</p>"
			],
			'omitted glue defaults to comma and space' => [ 'a,b,c|,|####|Hello, ####!', '<p>Hello, a!, Hello, b!, Hello, c!' . "\n</p>" ],
			'empty delimiter does not affect the glue' => [
				'a,b,c||####|Hello, ####!', '<p>Hello, a!, Hello, b!, Hello, c!' . "\n</p>"
			],
			'escaped mapping key is kept literally' => [
				'a,b,c|,|{{!}}|Hello, ####!', '<p>Hello, ####!, Hello, ####!, Hello, ####!' . "\n</p>"
			],
			'escaped mapping key in subject' => [
				'a,b,c|,|{{!}}|Hello, {{!}}!', '<p>Hello, |!, Hello, |!, Hello, |!' . "\n</p>"
			],
			'missing mapping key' => [ 'a,b,c|,||Hello, ####!', '' ],
			'missing delimiter and mapping key' => [ 'a,b,c|||Hello, ####!', '' ],
			'missing subject' => [ 'a,b,c|,|####', '' ],
			'only array' => [ 'a,b,c', '' ],
			'empty array' => [ '|,|####|Hello, ####!', '' ],
			'no arguments' => [ '', '' ],
		];
	}

	/**
	 * @dataProvider provideMappings
	 */
	public function testArrayMap( string $arguments, string $expectedHtml ): void {
		$this->assertParsesTo( $expectedHtml, "{{#complexarrayarraymap:$arguments}}" );
	}

	public static function providePrettyPrints(): array {
		return [
			'single item' => [ 'a', '<p>Hello, a!' . "\n</p>" ],
			'two items' => [ 'a,b', '<p>Hello, a! and Hello, b!' . "\n</p>" ],
			'three items' => [ 'a,b,c', '<p>Hello, a!, Hello, b! and Hello, c!' . "\n</p>" ],
		];
	}

	/**
	 * @dataProvider providePrettyPrints
	 */
	public function testPrettyPrint( string $items, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			"{{#complexarrayarraymap:$items|,|####|Hello, ####!|print=pretty}}"
		);
	}
}
