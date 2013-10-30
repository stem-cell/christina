<?php namespace Christina;

// Abstract interpretations of the query that called our scripts.
class Query
{
    // Is the query a valid post query?
    static function isPost()
    {
        return isset($_GET['post']) and filter_var($_GET['post'], FILTER_VALIDATE_INT) !== false;
    }

    // Is the query asking for JSON?
    static function isJson()
    {
        return isset($_GET['format']) and $_GET['format'] == 'json';
    }

    // Gets the raw decoded query string sent by the user agent.
    static function raw()
    {
        return rawurldecode($_SERVER['QUERY_STRING']);
    }

    // Gets the HTTP method used, e.g., "get" or "post".
    static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
