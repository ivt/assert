<?php

namespace IVT;

final class AssertionFailed extends \Exception {
    function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);

        // Add the $this field to the backtrace
        $prop = new \ReflectionProperty('Exception', 'trace');
        $prop->setAccessible(true);
        $prop->setValue($this, array_slice(debug_backtrace(), 1));
    }
}

