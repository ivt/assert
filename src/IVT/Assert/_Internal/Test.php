<?php

namespace IVT\Assert\_Internal;

use IVT\Assert;
use IVT\AssertionFailed;

class Test extends \PHPUnit_Framework_TestCase {
    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Expected 2, got 1
     */
    function testEqualFail() {
        Assert::equal(1, 2);
    }

    function testEqualPass() {
        Assert::equal(1, 1);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Key 'foo' does not exist
     */
    function testKeyExistsFail() {
        Assert::keyExists("foo", array("bar" => 7));
    }

    function testKeyExistsPass() {
        Assert::keyExists("bar", array("bar" => 7));
    }

    function testScalar() {
        Assert::scalar(null);
    }

    function testTruePass() {
        Assert::true(true);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Expected true, got NULL
     */
    function testTrueFail() {
        Assert::true(null);
    }

    function testFalsePass() {
        Assert::false(false);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Expected false, got 0
     */
    function testFalseFail() {
        Assert::false(0);
    }

    function testObjectPass() {
        Assert::object(new \stdClass);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed an object, got a null[]
     */
    function testObjectFail() {
        /** @noinspection PhpParamsInspection */
        Assert::object(array(null));
    }

    function testStringPass() {
        Assert::string('hello');
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a string, got a (bool|int|string)[]
     */
    function testStringFail() {
        Assert::string(array('string', 01, true));
    }

    function testFloatPass() {
        Assert::float(7.0);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a float, got a void[]
     */
    function testFloatFail() {
        /** @noinspection PhpParamsInspection */
        Assert::float(array());
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a float, got an int[]
     */
    function testTypeOfArray1() {
        /** @noinspection PhpParamsInspection */
        Assert::float(array(1));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a float, got a (float|int|string)[]
     */
    function testTypeOfArray3() {
        /** @noinspection PhpParamsInspection */
        Assert::float(array("foo", 2, 3.5));
    }

    function testArrayPass() {
        Assert::array_(array(null, 'herrderr'));
        /** @noinspection PhpDeprecationInspection */
        Assert::isArray(array(8));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed an array, got a stdClass
     */
    function testArrayFail() {
        /** @noinspection PhpParamsInspection */
        Assert::array_(new \stdClass);
    }

    function testResourcePass() {
        $resource = fopen('php://memory', 'rb');
        Assert::resource($resource);
        fclose($resource);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a resource, got a bool
     */
    function testResourceFail() {
        /** @noinspection PhpParamsInspection */
        Assert::resource(false);
    }

    function testIntPass() {
        Assert::int(-10);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed an int, got a float
     */
    function testIntFail() {
        Assert::int(-10.0);
    }

    function testBoolPass() {
        Assert::bool(false);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a bool, got an int
     */
    function testBoolFail() {
        /** @noinspection PhpParamsInspection */
        Assert::bool(10);
    }

    function testNullPass() {
        Assert::null(null);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a null, got a resource
     */
    function testNullFail() {
        /** @noinspection PhpParamsInspection */
        Assert::null(fopen('php://memory', 'rb'));
    }

    function testListPass() {
        $list = array(
            'hello',
            'bar',
            'baz',
        );

        Assert::list_($list);
        Assert::vector($list);
        Assert::notAssoc($list);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Expected 0, got 1
     */
    function testListFail() {
        $list = array(
            'hello',
            'bar',
            'baz',
        );
        unset($list[0]);

        Assert::list_($list);
    }

    function testInArrayPass() {
        Assert::in('bar', array('hello', 'bar', 'baz',));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage 'boo' must be one of: 'hello', 'bar', 'baz'
     */
    function testInArrayFail() {
        Assert::in('boo', array('hello', 'bar', 'baz',));
    }

    function testIntishPass() {
        Assert::intish(-2);
        Assert::intish(2.0);
        Assert::intish('2');
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage 2.1 was not intish
     */
    function testIntishFail() {
        Assert::intish(2.1);
    }

    function testSerialPass() {
        Assert::serial('78634');
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage '-78634' is not a positive integer :'(
     */
    function testSerialFail() {
        Assert::serial('-78634');
    }

    function testArraysOf() {
        $resource = fopen('php://memory', 'rb');

        Assert::ints(array(1, -923, 94765, PHP_INT_MAX));
        Assert::strings(array('aerghbearg', 'ierhbg'));
        Assert::floats(array(1.0, -98345.1, M_PI));
        Assert::objects(array(new \stdClass));
        Assert::resources(array($resource));
        Assert::bools(array(true, false, false, false, true, true, false, true));

        fclose($resource);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a resource[], got a bool[]
     */
    function testResourcesFail() {
        Assert::resources(array(true));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed an int[], got a bool[]
     */
    function testIntsFail() {
        Assert::ints(array(true));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a float[], got a bool[]
     */
    function testFloatsFail() {
        Assert::floats(array(true));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed an object[], got a bool[]
     */
    function testObjectsFail() {
        Assert::objects(array(true));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a string[], got a bool[]
     */
    function testStringsFail() {
        Assert::strings(array(true));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Needed a bool[], got an int[]
     */
    function testBoolsFail() {
        Assert::bools(array(7));
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage '0' should be truthy
     */
    function testTruthyFails() {
        Assert::truthy('0');
    }

    function testTruthyPass() {
        Assert::truthy(1);
    }

    /**
     * @expectedException \IVT\AssertionFailed
     * @expectedExceptionCode    0
     * @expectedExceptionMessage 1 should be falsy
     */
    function testFalsyFails() {
        Assert::falsy(1);
    }

    function testFalsyPass() {
        Assert::falsy(0);
    }

    function testTypeof() {
        self::assertEquals(Assert::typeof(null), 'null');
        self::assertEquals(Assert::typeof(true), 'bool');
        self::assertEquals(Assert::typeof(1), 'int');
        self::assertEquals(Assert::typeof(0.3), 'float');
        self::assertEquals(Assert::typeof(fopen('php://output', 'wb')), 'resource');
        self::assertEquals(Assert::typeof(new \stdClass), 'stdClass');
        self::assertEquals(Assert::typeof(array()), 'void[]');
        self::assertEquals(Assert::typeof(array(8)), 'int[]');
        self::assertEquals(Assert::typeof(array('string', 45.2)), '(float|string)[]');

        self::assertEquals(
            Assert::typeof(array(
                array('string'),
                array('string2', 45.2)
            )),
            '(float|string)[][]'
        );
    }

    function testThrow() {
        Assert::throws(function() {throw new \Exception;});
        Assert::throwsInstanceOf(function() {throw new \Exception;}, new \Exception);

        $e = null;
        try {
            Assert::throws(function() {});
        } catch (AssertionFailed $e) {
        }
        if (!$e)
            throw new AssertionFailed();

        $e = null;
        try {
            Assert::throwsInstanceOf(function() {}, new AssertionFailed);
        } catch (AssertionFailed $e) {
        }
        if (!$e)
            throw new AssertionFailed();

        $e = null;
        try {
            Assert::throwsInstanceOf(function() {throw new \Exception;}, new AssertionFailed);
        } catch (AssertionFailed $e) {
        }
        if (!$e)
            throw new AssertionFailed();
    }
}
