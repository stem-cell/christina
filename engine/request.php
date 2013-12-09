<?php namespace Christina;

// Abstract interpretations of the request that called our scripts.
class Request
{
    // Gets the raw decoded request path sent by the user agent.
    // Note that we now accept two forms:
    // * /christina.php?foo/bar
    // * /christina.php/foo/bar
    // The request path for that would be 'foo/bar'.
    static function raw()
    {
        $baseLength = strlen($_SERVER['SCRIPT_NAME']) + 1; // One more for the / or ?.
        $requestPath = substr($_SERVER['REQUEST_URI'], $baseLength);
        return rawurldecode($requestPath);
    }

    // Gets the HTTP method used, e.g., "get" or "post".
    static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Checks whether the current request path is empty.
    static function isEmpty()
    {
        return Request::raw() == '';
    }
}
