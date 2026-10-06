<?php

namespace ComplexArrays\Tests\Integration;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArraySortTest extends ComplexArraysIntegrationTestCase {

	private const ALPHABET = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';

	private static function listOf( array $items ): string {
		return '<ul><li>' . implode( "</li>\n<li>", $items ) . '</li></ul>';
	}

	public static function provideSortMethods(): array {
		$ascending = self::listOf( range( 'a', 'z' ) );
		$descending = self::listOf( range( 'z', 'a' ) );

		return [
			'multisort' => [ 'multisort', $ascending ],
			'asort' => [ 'asort', $ascending ],
			'arsort' => [ 'arsort', $descending ],
			'krsort' => [ 'krsort', $descending ],
			'natcasesort' => [ 'natcasesort', $ascending ],
			'natsort' => [ 'natsort', $ascending ],
			'rsort' => [ 'rsort', $descending ],
		];
	}

	/**
	 * @dataProvider provideSortMethods
	 */
	public function testSortsList( string $method, string $expectedHtml ): void {
		$this->assertParsesTo(
			$expectedHtml,
			'{{#complexarraydefine:example|' . self::ALPHABET . "}}{{#complexarraysort:example|$method}}"
				. "{{#complexarrayprint:example}}"
		);
	}

	public function testShuffleKeepsAllElements(): void {
		$this->assertParsesTo(
			self::listOf( range( 'a', 'z' ) ),
			'{{#complexarraydefine:example|' . self::ALPHABET . '}}{{#complexarraysort:example|shuffle}}'
				. '{{#complexarraysort:example|asort}}{{#complexarrayprint:example}}'
		);
	}

	public function testKeysortWithoutKeyYieldsError(): void {
		$html = $this->parse(
			'{{#complexarraydefine:example|' . self::ALPHABET . '}}{{#complexarraysort:example|keysort}}'
				. '{{#complexarrayprint:example}}'
		);

		$this->assertStringContainsString( 'Key must not be omitted when using keysort', $html );
		$this->assertStringContainsString( self::listOf( range( 'a', 'z' ) ), $html );
	}

	public function testKeysortWithZeroKeyKeepsOrder(): void {
		$this->assertParsesTo(
			self::listOf( range( 'a', 'z' ) ),
			'{{#complexarraydefine:example|' . self::ALPHABET . '}}{{#complexarraysort:example|keysort|0}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public function testKeysortWithKeyMissingInSubArraysYieldsError(): void {
		$html = $this->parse(
			'{{#complexarraydefine:example|' . self::ALPHABET . '}}{{#complexarraysort:example|keysort|1}}'
		);

		$this->assertStringContainsString( 'This sort key is not valid', $html );
	}

	public function testKeysortOrdersSubArraysByKey(): void {
		$this->assertParsesTo(
			"<ul><li>0\n<ul><li>a: a</li></ul></li>\n<li>1\n<ul><li>a: b</li></ul></li></ul>",
			'{{#complexarraydefine:example|[(("a": "b")), (("a": "a"))]}}{{#complexarraysort:example|keysort|a}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public static function provideInvalidKeysortKeys(): array {
		return [
			'key missing in all sub-arrays' => [ '[(("b": "b")), (("b": "a"))]' ],
			'key missing in some sub-arrays' => [ '[(("a": "b")), (("b": "a"))]' ],
		];
	}

	/**
	 * @dataProvider provideInvalidKeysortKeys
	 */
	public function testKeysortWithInvalidKeyYieldsErrorAndKeepsOrder( string $definition ): void {
		$html = $this->parse(
			"{{#complexarraydefine:example|$definition}}{{#complexarraysort:example|keysort|a}}"
				. "{{#complexarrayprint:example}}"
		);

		$this->assertStringContainsString( 'This sort key is not valid', $html );
		$this->assertStringContainsString( '<li>0', $html );
	}

	public function testDefaultsToSortWithoutOptions(): void {
		$this->assertParsesTo(
			self::listOf( [ '1', '2', '3' ] ),
			'{{#complexarraydefine:example|3,1,2}}{{#complexarraysort:example}}{{#complexarrayprint:example}}'
		);
	}

	public function testKeysortDescendingReversesOrder(): void {
		$this->assertParsesTo(
			"<ul><li>0\n<ul><li>a: b</li></ul></li>\n<li>1\n<ul><li>a: a</li></ul></li></ul>",
			'{{#complexarraydefine:example|[(("a": "a")), (("a": "b"))]}}{{#complexarraysort:example|keysort,desc|a}}'
				. '{{#complexarrayprint:example}}'
		);
	}

	public function testKeysortWithNestedKeyValueYieldsError(): void {
		$this->assertStringContainsString(
			'error',
			$this->parse(
				'{{#complexarraydefine:example|[(("a": ["x"])), (("a": ["y"]))]}}'
					. '{{#complexarraysort:example|keysort|a}}'
			)
		);
	}

	public function testMissingNameYieldsError(): void {
		$this->assertStringContainsString( 'error', $this->parse( '{{#complexarraysort:}}' ) );
	}

	public function testUndefinedArrayPrintsNothing(): void {
		$this->assertParsesTo( '', '{{#complexarraysort:foobar}}{{#complexarrayprint:foobar}}' );
	}
}
