<?php namespace Christina;

// Note that this file contains the logic to handle routes, not the routes themselves.

// Class to contain routes and operate on them.
class Routes
{
    // Array of GET routes.
    static $get = [];

    // Array of POST routes.
    static $post = [];

    // Regular expression that represents a valid route.
    // It includes named subpatterns for 'name' and 'params' of the route.
    static $pattern = '~^/(?<name>[a-z_]+[a-z0-9_-]*)(/(?<params>.*))?$~i';

    // Checks if the current route is valid.
    static function isValid()
    {
        $valid = preg_match(self::$pattern, Query::raw(), $route);
        if (!$valid) return false;
        $routes = self::forMethod(Query::method());
        return isset($routes[$route['name']]);
    }

    // Lists all the routes for the given http method.
    static function forMethod($method)
    {
        switch (strtolower($method))
        {
            case 'get': return self::$get;
            case 'post': return self::$post;
            default: die;
        }
    }

    // Gets the current route, and returns a map of its 'name' (string),
    // 'method' (string), 'code' (function) and 'params' (string).
    // Be sure to check if isValid() first.
    static function get()
    {
        preg_match(self::$pattern, Query::raw(), $route);
        $routes = self::forMethod(Query::method());

        return
        [
            'name' => $route['name'],
            'method' => Query::method(),
            'code' => $routes[$route['name']],
            'params' => isset($route['params']) ? $route['params'] : ''
        ];
    }

    // Calls the current route with its params.
    static function call()
    {
        $route = self::get();
        $route['code']($route['params']);
    }
}
