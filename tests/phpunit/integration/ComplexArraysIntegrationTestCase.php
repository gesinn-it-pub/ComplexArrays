<?php

namespace ComplexArrays\Tests\Integration;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use WSArrays;

/**
 * Shared base for integration tests of the ComplexArrays parser functions.
 *
 * Parses wikitext with the real parser and returns the resulting HTML, so
 * tests express the intended behaviour of a parser function in wikitext.
 *
 * @group Database
 */
abstract class ComplexArraysIntegrationTestCase extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		// Defined arrays live in a static property and would leak between tests.
		WSArrays::$arrays = [];
		$GLOBALS['wfDefinedArraysGlobal'] = [];
	}

	protected function tearDown(): void {
		WSArrays::$arrays = [];
		parent::tearDown();
	}

	/**
	 * Parses the given wikitext and returns the HTML, without the parser's
	 * wrapper element and surrounding whitespace.
	 */
	protected function parse( string $wikitext ): string {
		$services = MediaWikiServices::getInstance();
		$parser = $services->getParserFactory()->create();
		$title = \Title::makeTitle( NS_MAIN, 'ComplexArraysTest' );
		$options = ParserOptions::newFromAnon();

		$html = $parser->parse( $wikitext, $title, $options )->getText( [
			'unwrap' => true,
			'allowTOC' => false,
		] );

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
