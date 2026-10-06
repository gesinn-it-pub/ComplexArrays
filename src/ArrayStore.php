<?php

namespace ComplexArrays;

use Parser;
use WeakMap;

/**
 * The arrays defined while a parser parses a page.
 *
 * Every parser has its own store, so arrays never leak between pages, previews,
 * jobs or API parses. The store is emptied whenever the parser clears its state,
 * i.e. at the start of each top-level parse. Templates transcluded during a parse
 * use the same parser and therefore share the store with the page.
 */
class ArrayStore {
	/** @var WeakMap<Parser, ArrayStore>|null */
	private static $stores = null;

	/** @var ComplexArray[] Defined arrays by name */
	private $arrays = [];

	/**
	 * @param Parser $parser
	 * @return ArrayStore
	 */
	public static function forParser( Parser $parser ): ArrayStore {
		self::$stores ??= new WeakMap();
		self::$stores[$parser] ??= new self();

		return self::$stores[$parser];
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has( $name ): bool {
		return isset( $this->arrays[$name] );
	}

	/**
	 * @param string $name
	 * @return ComplexArray|null
	 */
	public function get( $name ): ?ComplexArray {
		return $this->arrays[$name] ?? null;
	}

	/**
	 * @param string $name
	 * @param ComplexArray $array
	 */
	public function set( $name, ComplexArray $array ): void {
		$this->arrays[$name] = $array;
	}

	/**
	 * @param string $name
	 */
	public function remove( $name ): void {
		unset( $this->arrays[$name] );
	}

	/**
	 * @return string[]
	 */
	public function names(): array {
		return array_map( 'strval', array_keys( $this->arrays ) );
	}

	/**
	 * Add the given arrays, replacing arrays of the same name.
	 *
	 * @param ComplexArray[] $arrays
	 */
	public function merge( array $arrays ): void {
		$this->arrays = array_merge( $this->arrays, $arrays );
	}

	/**
	 * Remove all arrays.
	 */
	public function clear(): void {
		$this->arrays = [];
	}
}
