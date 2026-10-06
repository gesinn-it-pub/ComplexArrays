<?php

/**
 * ComplexArrays - Associative and multidimensional arrays for MediaWiki.
 * Copyright (C) 2019 Marijn van Wezel
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace ComplexArrays;

/**
 * Class ComplexArrays
 *
 * Defines all parser functions.
 *
 * @extends GlobalFunctions
 */
class ComplexArrays extends ComplexArray {
	/**
	 * This variable holds all defined arrays. If an array is defined called "array", the array will be stored in
	 * ComplexArrays::$arrays["array"].
	 *
	 * @var array
	 */
	public static $arrays = [];
}
