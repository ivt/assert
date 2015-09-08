<?php

namespace IVT\AssertTest;

use IVT\Assert;
use IVT\AssertionFailed;

class AssertTest extends \PHPUnit_Framework_TestCase
{
    function testEqual()
    {
        try {
            Assert::equal( 1, 2 );
        }
        catch ( AssertionFailed $e ) {
            return;
        }
        $this->fail( "Expected exception not raised." );
    }

    function testKeyExists()
    {
        try {
            Assert::keyExists( "foo", array( "bar" => 7 ) );
        }
        catch ( AssertionFailed $e ) {
            return;
        }
        $this->fail( "Expected exception not raised." );
    }

    function testScalar()
    {
        $this->assertEquals( Assert::scalar( 7 ), 7 );
    }
}
