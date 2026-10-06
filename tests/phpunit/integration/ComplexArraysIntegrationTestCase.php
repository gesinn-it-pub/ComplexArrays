<?php

namespace ComplexArrays\Tests\Integration;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use Parser;
use ParserOptions;

/**
 * Shared base for integration tests of the ComplexArrays parser functions.
 *
 * Parses wikitext with the real parser and returns the resulting HTML, so
 * tests express the intended behaviour of a parser function in wikitext.
 *
 * Tests deliberately carry no @covers annotation: every parser function runs
 * through the shared infrastructure (GlobalFunctions, ComplexArrays),
 * which would otherwise not count as covered.
 *
 * @group Database
 */
abstract class ComplexArraysIntegrationTestCase extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wgComplexArraysDefinedArrays'] = [];
	}

	/**
	 * Parses the given wikitext and returns the HTML, without the parser's
	 * wrapper element and surrounding whitespace.
	 */
	protected function parse( string $wikitext ): string {
		return $this->parseWith( MediaWikiServices::getInstance()->getParserFactory()->create(), $wikitext );
	}

	/**
	 * Like parse(), but with the given parser, so a test can parse several
	 * pages with one parser or with different ones.
	 */
	protected function parseWith( Parser $parser, string $wikitext ): string {
		$title = \Title::makeTitle( NS_MAIN, 'ComplexArraysTest' );
		$options = ParserOptions::newFromAnon();

		$html = $parser->parse( $wikitext, $title, $options )->getText( [
			'unwrap' => true,
			'allowTOC' => false,
		] );

		// Newer MediaWiki cores flag empty list items with a class; that is incidental here.
		$html = str_replace( ' class="mw-empty-elt"', '', $html );

		return trim( preg_replace( '/<!--.*?-->/s', '', $html ) );
	}

	/**
	 * Asserts that the wikitext renders to the expected HTML.
	 */
	protected function assertParsesTo( string $expectedHtml, string $wikitext, string $message = '' ): void {
		$this->assertSame( $expectedHtml, $this->parse( $wikitext ), $message );
	}

	/**
	 * Asserts that the wikitext renders to the expected text, ignoring the
	 * paragraph wrapper the parser puts around plain output.
	 */
	protected function assertParsesToText( string $expected, string $wikitext, string $message = '' ): void {
		$html = $this->parse( $wikitext );
		$text = trim( preg_replace( '#^<p>(.*)</p>$#s', '$1', $html ) );
		$this->assertSame( $expected, $text, $message );
	}
}
