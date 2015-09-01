<?php

namespace IVTLibrary;

class Assert
{
	static function equal( $actual, $expected, $message = '' )
	{
		if ( $actual === $expected )
			return;

		$expected = self::dump( $expected );
		$actual   = self::dump( $actual );

		throw new AssertionFailed( $message ?: "Expected $expected, got $actual" );
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	static function dump( $value )
	{
		return var_export( $value, true );
	}

	/**
	 * @param mixed $key
	 * @param array $array
	 * @param string $message
	 */
	static function keyExists( $key, array $array, $message = '' )
	{
		self::true( array_key_exists( $key, $array ), $message );
	}

	static function true( $actual, $message = '' )
	{
		self::equal( $actual, true, $message );
	}

	/**
	 * @param object $object
	 * @param string $message
	 * @return object
	 */
	static function object( $object, $message = '' )
	{
		self::type( $object, 'object', $message );
		return $object;
	}

	/**
	 * @param bool|float|int|null|string $value
	 * @return bool|float|int|null|string
	 */
	static function scalar( $value )
	{
		self::in( gettype( $value ), array( 'boolean', 'integer', 'double', 'string', 'NULL' ) );
		return $value;
	}

	private static function type( $value, $type, $message = '' )
	{
		self::equal( gettype( $value ), $type, $message );
	}

	/**
	 * @param string $value
	 * @param string $message
	 * @return string
	 * @throws AssertionFailed
	 */
	static function string( $value, $message = '' )
	{
		if ( !is_string( $value ) )
			self::type( $value, 'string', $message );
		return $value;
	}

	/**
	 * @param float  $value
	 * @param string $message
	 * @return float
	 * @throws AssertionFailed
	 */
	static function float( $value, $message = '' )
	{
		if ( !is_float( $value ) )
			self::type( $value, 'double', $message );
		return $value;
	}

	/**
	 * @param array  $value
	 * @param string $message
	 * @return array
	 * @throws AssertionFailed
	 */
	static function isArray( $value, $message = '' )
	{
		if ( !is_array( $value ) )
			self::type( $value, 'array', $message );
		return $value;
	}

	/**
	 * @param resource $value
	 * @param string   $message
	 * @return resource
	 * @throws AssertionFailed
	 */
	static function resource( $value, $message = '' )
	{
		if ( !is_resource( $value ) )
			self::type( $value, 'resource', $message );
		return $value;
	}

	/**
	 * @param int    $value
	 * @param string $message
	 * @return int
	 * @throws AssertionFailed
	 */
	static function int( $value, $message = '' )
	{
		if ( !is_int( $value ) )
			self::type( $value, 'integer', $message );
		return $value;
	}

	/**
	 * @param bool   $value
	 * @param string $message
	 * @return bool
	 * @throws AssertionFailed
	 */
	static function bool( $value, $message = '' )
	{
		if ( !is_bool( $value ) )
			self::type( $value, 'boolean', $message );
		return $value;
	}

	/**
	 * @param null   $value
	 * @param string $message
	 * @return null
	 * @throws AssertionFailed
	 */
	static function null( $value, $message = '' )
	{
		if ( !is_null( $value ) )
			self::type( $value, 'NULL', $message );
		return $value;
	}

	static function false( $actual, $message = '' )
	{
		self::equal( $actual, false, $message );
	}

	static function notAssoc( array $array )
	{
		Assert::isArray( $array );
		$i = 0;
		foreach ( $array as $k => $v )
			Assert::equal( $k, $i++ );
		return $array;
	}

	static function in( $value, array $values )
	{
		self::isArray( $values );

		if ( in_array( $value, $values, true ) )
			return $value;

		$value  = self::dump( $value );
		$values = self::dump( $values );

		throw new AssertionFailed( "$value must be one of: $values");
	}

	static function intish( $value )
	{
		if ( is_int( $value ) )
			return $value;
		else if ( is_string( $value ) )
		{
			if ( (string)(int)$value === $value )
				return $value;
		}
		else if ( is_float( $value ) )
		{
			if ( (float)(int)$value === $value )
				return $value;
		}

		throw new AssertionFailed( "$value was not intish" );
	}

	static function serial( $value )
	{
		self::intish( $value );

		if ( $value > 0 )
			return $value;

		throw new AssertionFailed( "'{$value}' is not a positive integer :'(" );
	}
}

class AssertionFailed extends \FailWhale\Exception
{
}
