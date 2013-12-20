<?php namespace Christina;

// This file defines Exceptions that we might want to throw throughout Christina.

// Base exception class for Christina.
class ChristinaException extends \RuntimeException {}

// Exception that indicates a parameter was faulty.
class ParameterException extends ChristinaException {}

// Exception that indicates a type mismatch.
class TypeException extends ChristinaException {}

// Exception thrown from a route parameter parser.
class ParserException extends ChristinaException {}

// Exception thrown when a feature was not implemented.
class UnimplementedException extends ChristinaException
{
    // Feature name.
    public $feature = '';

    // Build from a given feature name, or none at all.
    function __construct($feature = '')
    {
        $this->feature = $feature;
    }
}

// If this exception gets thrown, please give me a cup of coffee.
class UnreachableException extends ChristinaException {}
