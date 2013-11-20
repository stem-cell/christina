<?php namespace Christina;

// Abstract interpretations of the query that called our scripts.
class Query
{
    // Gets the raw decoded query sent by the user agent.
    // Note that we now accept two forms:
    // * /christina.php?foo/bar
    // * /christina.php/foo/bar
    // The query for that would be 'foo/bar'.
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
