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

// If this exception gets thrown, please give me a cup of coffee.
class UnreachableException extends ChristinaException {}
