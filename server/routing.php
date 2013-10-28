<?php namespace Christina;

// Note that this file contains the logic to handle routes, not the routes themselves.

// Class to contain routes and operate on them.
class Routes
{
    // Array of all routes.
    static $all = [];

    // Register a route.
    static function register($name, $code)
    {
        self::$all[$name] = $code;
    }
}
