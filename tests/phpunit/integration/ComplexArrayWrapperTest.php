<?php

namespace ComplexArrays\Tests\Integration;

use ComplexArray;
use ComplexArrayWrapper;

// The test classes are not registered with the autoloader when run via vendor/bin/phpunit.
require_once __DIR__ . '/ComplexArraysIntegrationTestCase.php';

/**
 * @group Database
 */
class ComplexArrayWrapperTest extends ComplexArraysIntegrationTestCase {

	public function testNewFromVoidCreatesWrapper(): void {
		$this->assertInstanceOf( ComplexArrayWrapper::class, ComplexArrayWrapper::newFromVoid() );
	}

	public function testOnCreatesMissingArray(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid();

		$this->assertSame( $wrapper, $wrapper->on( 'example' ) );
		$this->assertSame( [], $wrapper->get() );
	}

	public function testOnRejectsNonStringName(): void {
		$this->assertFalse( ComplexArrayWrapper::newFromVoid()->on( [ 'example' ] ) );
	}

	public function testSetDefinesArray(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );

		$this->assertTrue( $wrapper->set( [ 'a', 'b' ] ) );
		$this->assertSame( [ 'a', 'b' ], $wrapper->get() );
	}

	public function testSetWithIndicesCreatesNestedPath(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );

		$this->assertTrue( $wrapper->in( [ 'foo', 'bar' ] )->set( 'baz' ) );
		$this->assertSame( 'baz', $wrapper->get() );
		$this->assertSame( [ 'foo' => [ 'bar' => 'baz' ] ], $wrapper->in( [] )->get() );
	}

	public function testSetWithIndicesKeepsExistingValues(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );
		$wrapper->set( [ 'foo' => [ 'x' => 1 ] ] );

		$wrapper->in( [ 'foo', 'y' ] )->set( 2 );

		$this->assertSame( [ 'foo' => [ 'x' => 1, 'y' => 2 ] ], $wrapper->in( [] )->get() );
	}

	public function testGetFollowsIndices(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );
		$wrapper->set( [ 'foo' => [ 'bar' ] ] );

		$this->assertSame( 'bar', $wrapper->in( [ 'foo', 0 ] )->get() );
	}

	public function testGetReturnsFalseForUnknownIndex(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );
		$wrapper->set( [ 'a' ] );

		$this->assertFalse( $wrapper->in( [ 'missing' ] )->get() );
	}

	public function testGetAndSetFailWithoutArrayName(): void {
		$wrapper = new ComplexArrayWrapper();
		$wrapper->array_name = '';

		$this->assertFalse( $wrapper->get() );
		$this->assertFalse( $wrapper->set( 'a' ) );
		$this->assertFalse( $wrapper->unsetArray() );
	}

	public function testGetReturnsFalseWhenArrayWasRemoved(): void {
		$wrapper = new ComplexArrayWrapper();
		$wrapper->array_name = 'removed';

		$this->assertFalse( $wrapper->get() );
	}

	public function testUnsetArrayRemovesArray(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' );
		$wrapper->set( [ 'a' ] );

		$this->assertTrue( $wrapper->unsetArray() );
		$this->assertArrayNotHasKey( 'example', $wrapper->arrays );
	}

	public function testUnsetArrayRefusesWhenIndicesAreSet(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid()->on( 'example' )->in( [ 'a' ] );

		$this->assertFalse( $wrapper->unsetArray() );
		$this->assertInstanceOf( ComplexArray::class, $wrapper->arrays['example'] );
	}

	public function testResetReturnsWrapper(): void {
		$wrapper = ComplexArrayWrapper::newFromVoid();

		$this->assertSame( $wrapper, $wrapper->reset() );
	}
}
