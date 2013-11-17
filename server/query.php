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

    // Gets the raw decoded query sent by the user agent.
    // Note that we now accept two forms:
    // * /christina.php?foo/bar
    // * /christina.php/foo/bar
    static function raw()
    {
        $baseLength = strlen($_SERVER['SCRIPT_NAME']) + 1; // One more for the / or ?.
        $query = substr($_SERVER['REQUEST_URI'], $baseLength);
        return rawurldecode($query);
    }

    // Gets the HTTP method used, e.g., "get" or "post".
    static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
