<?php

namespace IVT;

use IVT\Assert\_Internal\PHPType\Type;

final class Assert {
    static function equal($actual, $expected, $message = '') {
        if ($actual === $expected)
            return;

        $expected = self::dump($expected);
        $actual   = self::dump($actual);

        throw new AssertionFailed($message ?: "Expected $expected, got $actual");
    }

    /**
     * @param mixed $value
     * @return string
     */
    private static function dump($value) {
        return var_export($value, true);
    }

    /**
     * Return the type of the given value in a PhpDoc-style syntax
     * @param mixed $value
     * @return string
     * @throws \Exception
     */
    static function typeof($value) {
        return Type::fromValue($value)->toString();
    }

    /**
     * @param mixed  $key
     * @param array  $array
     * @param string $message
     * @return mixed
     * @throws AssertionFailed
     */
    static function keyExists($key, array $array, $message = '') {
        if (!array_key_exists($key, $array)) {
            throw new AssertionFailed($message ?: "Key '$key' does not exist");
        } else {
            return $array[$key];
        }
    }

    private static function an($thing) {
        static $vowels = array('a', 'e', 'i', 'o', 'u');
        return in_array(strtolower(substr($thing, 0, 1)), $vowels, true) ? "an $thing" : "a $thing";
    }

    static function true($actual, $message = '') {
        self::equal($actual, true, $message);
    }

    /**
     * @param object $value
     * @param string $message
     * @return object
     * @throws AssertionFailed
     */
    static function object($value, $message = '') {
        if (!is_object($value))
            throw self::wrongType($value, 'object', $message);
        return $value;
    }

    /**
     * Any type besides array, object and resource
     * @param bool|float|int|null|string $value
     * @param string                     $message
     * @return bool|float|int|null|string
     * @throws AssertionFailed
     */
    static function scalar($value, $message = '') {
        if (!is_scalar($value) && !is_null($value))
            throw self::wrongType($value, 'bool|float|int|null|string', $message);
        return $value;
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @param string $message
     * @return AssertionFailed
     * @throws \Exception
     */
    private static function wrongType($value, $type, $message = '') {
        return new AssertionFailed($message ?: "Needed " . self::an($type) . ", got " . self::an(self::typeof($value)));
    }

    /**
     * @param string $value
     * @param string $message
     * @return string
     * @throws AssertionFailed
     */
    static function string($value, $message = '') {
        if (!is_string($value))
            throw self::wrongType($value, 'string', $message);
        return $value;
    }

    /**
     * @param float  $value
     * @param string $message
     * @return float
     * @throws AssertionFailed
     */
    static function float($value, $message = '') {
        if (!is_float($value))
            throw self::wrongType($value, 'float', $message);
        return $value;
    }

    /**
     * @deprecated
     * @see array_
     * @param array  $value
     * @param string $message
     * @return array
     * @throws AssertionFailed
     */
    static function isArray($value, $message = '') {
        return self::array_($value, $message);
    }

    /**
     * @param array  $value
     * @param string $message
     * @return array
     * @throws AssertionFailed
     */
    static function array_($value, $message = '') {
        if (!is_array($value))
            throw self::wrongType($value, 'array', $message);
        return $value;
    }

    /**
     * @param resource $value
     * @param string   $message
     * @return resource
     * @throws AssertionFailed
     */
    static function resource($value, $message = '') {
        if (!is_resource($value))
            throw self::wrongType($value, 'resource', $message);
        return $value;
    }

    /**
     * @param int    $value
     * @param string $message
     * @return int
     * @throws AssertionFailed
     */
    static function int($value, $message = '') {
        if (!is_int($value))
            throw self::wrongType($value, 'int', $message);
        return $value;
    }

    /**
     * @param bool   $value
     * @param string $message
     * @return bool
     * @throws AssertionFailed
     */
    static function bool($value, $message = '') {
        if (!is_bool($value))
            throw self::wrongType($value, 'bool', $message);
        return $value;
    }

    /**
     * @param null   $value
     * @param string $message
     * @return null
     * @throws AssertionFailed
     */
    static function null($value, $message = '') {
        if (!is_null($value))
            throw self::wrongType($value, 'null', $message);
        return $value;
    }

    static function false($actual, $message = '') {
        self::equal($actual, false, $message);
    }

    /**
     * Asserts that the given array has keys of the form 0, 1, 2...N
     * @param array $array
     * @return array
     * @throws AssertionFailed
     */
    static function list_(array $array) {
        self::array_($array);
        $i = 0;
        foreach ($array as $k => $v)
            self::equal($k, $i++);
        return $array;
    }

    /**
     * @see list_
     * @param array $array
     * @return array
     */
    static function vector(array $array) {
        return self::list_($array);
    }

    /**
     * @see list_
     * @param array $array
     * @return array
     */
    static function notAssoc(array $array) {
        return self::list_($array);
    }

    /**
     * Assert the value is in the array. Comparison is done strictly (===).
     * @param mixed $value
     * @param array $values
     * @return string
     * @throws AssertionFailed
     */
    static function in($value, array $values) {
        self::array_($values);

        if (in_array($value, $values, true))
            return $value;

        $oneOf = array();
        foreach ($values as $v)
            $oneOf[] = self::dump($v);

        throw new AssertionFailed(self::dump($value) . " must be one of: " . join(', ', $oneOf));
    }

    /**
     * Assert that something is an int or an int stored in a float or string
     * Eg: 15 (int), "15" (string), 15.0 (float), but not "hello15" (string) or 15.1 (float)
     * @param int|float|string $value
     * @return int|float|string
     * @throws AssertionFailed
     */
    static function intish($value) {
        $int = (int)$value;
        if (
            $value === $int ||
            $value === (string)$int ||
            $value === (float)$int
        ) {
            return $value;
        } else {
            throw new AssertionFailed("$value was not intish");
        }
    }

    /**
     * Same as intish() except must be > 0. Useful for database IDs.
     * @param int|float|string $value
     * @see intish
     * @return int|float|string
     * @throws AssertionFailed
     */
    static function serial($value) {
        self::intish($value);

        if ($value > 0) {
            return $value;
        } else {
            throw new AssertionFailed("'$value' is not a positive integer :'(");
        }
    }

    /**
     * @param int[]  $ints
     * @param string $message
     * @return \int[]
     * @throws AssertionFailed
     */
    static function ints(array $ints, $message = '') {
        self::array_($ints);
        foreach ($ints as $int) {
            if (!is_int($int)) {
                throw self::wrongType($ints, 'int[]', $message);
            }
        }
        return $ints;
    }

    /**
     * @param string[] $strings
     * @param string   $message
     * @return \string[]
     * @throws AssertionFailed
     */
    static function strings(array $strings, $message = '') {
        self::array_($strings);
        foreach ($strings as $string) {
            if (!is_string($string)) {
                throw self::wrongType($strings, 'string[]', $message);
            }
        }
        return $strings;
    }

    /**
     * @param float[] $floats
     * @param string  $message
     * @return \float[]
     * @throws AssertionFailed
     */
    static function floats(array $floats, $message = '') {
        self::array_($floats);
        foreach ($floats as $float) {
            if (!is_float($float)) {
                throw self::wrongType($floats, 'float[]', $message);
            }
        }
        return $floats;
    }

    /**
     * @param object[] $objects
     * @param string   $message
     * @return \object[]
     * @throws AssertionFailed
     */
    static function objects(array $objects, $message = '') {
        self::array_($objects);
        foreach ($objects as $object) {
            if (!is_object($object)) {
                throw self::wrongType($objects, 'object[]', $message);
            }
        }
        return $objects;
    }

    /**
     * @param resource[] $resources
     * @param string     $message
     * @return \resource[]
     * @throws AssertionFailed
     */
    static function resources(array $resources, $message = '') {
        self::array_($resources);
        foreach ($resources as $resource) {
            if (!is_resource($resource)) {
                throw self::wrongType($resources, 'resource[]', $message);
            }
        }
        return $resources;
    }

    /**
     * @param bool[] $bools
     * @param string $message
     * @return \bool[]
     * @throws AssertionFailed
     */
    static function bools(array $bools, $message = '') {
        self::array_($bools);
        foreach ($bools as $bool) {
            if (!is_bool($bool)) {
                throw self::wrongType($bools, 'bool[]', $message);
            }
        }
        return $bools;
    }

    /**
     * @param mixed  $value
     * @param string $message
     * @return mixed
     * @throws AssertionFailed
     */
    static function truthy($value, $message = '') {
        if (!$value)
            throw new AssertionFailed($message ?: self::dump($value) . " should be truthy");
        else
            return $value;
    }

    /**
     * @param mixed  $value
     * @param string $message
     * @return mixed
     * @throws AssertionFailed
     */
    static function falsy($value, $message = '') {
        if ($value)
            throw new AssertionFailed($message ?: self::dump($value) . " should be falsy");
        else
            return $value;
    }

    static function throws(\Closure $closure, $message = '') {
        try {
            $ret = $closure();
        }
        catch (\Exception $e) {
            return $e;
        }

        $val = self::dump($ret);
        throw new AssertionFailed($message ?: "Expected closure to throw, but it returned $val" );
    }

    static function throwsInstanceOf(\Closure $closure, \Exception $instanceOfSameType, $message = '') {
        try {
            $ret = $closure();
        }
        catch (\Exception $e) {
            $actualType = get_class($e);
            $expectedType = get_class($instanceOfSameType);
            if ($expectedType === $actualType)
                return $e;
            else
                throw new AssertionFailed($message ?: "Expected exception of type $expectedType, but it was of type $actualType");
        }

        $val = self::dump($ret);
        throw new AssertionFailed($message ?: "Expected closure to throw, but it returned $val" );
    }
}

