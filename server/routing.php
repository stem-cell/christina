<?php namespace Christina;

// Note that this file contains the logic to handle routes, not the routes themselves.

// Class to contain routes and operate on them.
class Routes
{
    // Array of GET routes.
    static $get = [];

    // Array of POST routes.
    static $post = [];

    // Stores parameter handlers. They should either be a route
    // name (like 'features') or a lowercase HTTP method and a
    // route name separated by a pipe (as in 'get|features').
    // These will be called with the parameter string and should
    // either return a parsed, sanitized representation of the
    // parameter string from the query or raise an exception.
    static $param = [];

    // Regular expression that represents a valid route.
    // It includes named subpatterns for 'name' and 'params' of the route.
    static $pattern = '~^/?(?<name>[a-z_]+[a-z0-9_-]*)(/(?<params>.*))?$~i';

    // Checks if the current route is valid.
    static function isValid()
    {
        $valid = preg_match(Routes::$pattern, Query::raw(), $route);
        if (!$valid) return false;
        $routes = Routes::forMethod(Query::method());
        return isset($routes[$route['name']]);
    }

    // Lists all the routes for the given http method.
    static function forMethod($method)
    {
        switch (strtolower($method))
        {
            case 'get': return Routes::$get;
            case 'post': return Routes::$post;
            default: die;
        }
    }

    // Gets the current route, and returns a map of its 'name' (string),
    // 'method' (string), 'code' (function) and 'params' (string).
    // Be sure to check if isValid() first.
    static function get()
    {
        preg_match(Routes::$pattern, Query::raw(), $route);
        $routes = Routes::forMethod(Query::method());

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
        $route = Routes::get();
        // This next line now might throw an exception. TODO: handle it.
        $params = Routes::parseParameters($route);
        $route['code']($params);
    }

    // If a parameter handler is defined for a route or method|route,
    // this will invoke it to parse the parameter data and return the
    // result, bubbling any exceptions thrown.
    // In case no handler is defined, it will return the params as-is.
    static function parseParameters($route)
    {
        $method = $route['method'];
        $name = $route['name'];

        foreach (["$method|$name", $name] as $variant)
        {
            if (isset(Routes::$param[$variant]))
            {
                $closure = Routes::$param[$variant];
                return $closure($route['params']);
            }
        }

        return $route['params'];
    }
}
