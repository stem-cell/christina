<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Error handler parameters - simple format of /viewer/[123] where 123 is the error code.
Routes::$param['error'] = function($params)
{
    // Supported error codes are:
    // 400 Bad Request
    // 401 Unauthorized
    // 403 Forbidden
    // 404 Not Found
    // 405 Method Not Allowed
    // 410 Gone
    // 500 Internal Server Error
    // 501 Not Implemented
    // 503 Service Unavailable
    $re = '~^(?<code>400|401|403|404|405|410|500|501|503)$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'code' => $matches['code']
        ];
    }
    else
    {
        throw new ParserException();
    }
};
