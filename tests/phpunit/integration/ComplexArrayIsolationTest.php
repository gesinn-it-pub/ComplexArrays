<?php

namespace ComplexArrays\Tests\Integration;

use MediaWiki\MediaWikiServices;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * Arrays and call state belong to one parser and must not leak into other
 * pages, previews, jobs or API parses.
 *
 * @group Database
 */
class ComplexArrayIsolationTest extends ComplexArraysIntegrationTestCase {

	private function newParser(): \Parser {
		return MediaWikiServices::getInstance()->getParserFactory()->create();
	}

	public function testArraysDoNotLeakBetweenParsers(): void {
		$this->parseWith( $this->newParser(), '{{#complexarraydefine:example|a,b,c}}' );

		$this->assertSame(
			'',
			$this->parseWith( $this->newParser(), '{{#complexarrayprint:example}}' )
		);
	}

	public function testArraysDoNotLeakBetweenParsesOfOneParser(): void {
		$parser = $this->newParser();
		$this->parseWith( $parser, '{{#complexarraydefine:example|a,b,c}}' );

		$this->assertSame( '', $this->parseWith( $parser, '{{#complexarrayprint:example}}' ) );
	}

	public function testDefinedArrayListIsLimitedToCurrentParse(): void {
		$parser = $this->newParser();
		$this->parseWith( $parser, '{{#complexarraydefine:first|a,b}}' );

		$html = $this->parseWith(
			$parser,
			'{{#complexarraydefine:second|c,d}}{{#complexarraydefinedarrays:names}}{{#complexarrayprint:names}}'
		);

		$this->assertStringContainsString( 'second', $html );
		$this->assertStringNotContainsString( 'first', $html );
	}

	public function testTranscludedTemplateSharesArraysWithPage(): void {
		$this->editPage( 'Template:ComplexArraysDefine', '{{#complexarraydefine:example|a,b,c}}' );

		$this->assertParsesToText(
			'b',
			'{{ComplexArraysDefine}}{{#complexarrayprint:example[1]}}'
		);
	}

	public function testArraysDefinedByPageAreVisibleInTranscludedTemplate(): void {
		$this->editPage( 'Template:ComplexArraysPrint', '{{#complexarrayprint:example[2]}}' );

		$this->assertParsesToText(
			'c',
			'{{#complexarraydefine:example|a,b,c}}{{ComplexArraysPrint}}'
		);
	}

	public function testMapSeparatorDoesNotSurviveToNextCall(): void {
		$this->assertParsesToText(
			'a;b;cabc',
			'{{#complexarraydefine:example|a,b,c}}'
				. '{{#complexarraymap:example|v|v|;}}{{#complexarraymap:example|v|v}}'
		);
	}

	public function testMapShowFlagDoesNotSurviveToNextCall(): void {
		$parser = $this->newParser();
		$define = '{{#complexarraydefine:example|(("a":(("b":"c"))))}}';

		$this->assertStringContainsString(
			'[x]',
			$this->parseWith( $parser, $define . '{{#complexarraymap:example|v|v[x]||true}}' )
		);
		$this->assertSame(
			'',
			$this->parseWith( $parser, $define . '{{#complexarraymap:example|v|v[x]}}' )
		);
	}
}
