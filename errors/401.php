<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 401 Unauthorized error.
class Http401Error extends HttpError
{
    const code = 401;

    const name = 'Unauthorized';

    public $description = 'The request requires authentication.';

    function setHeaders()
    {
        parent::setHeaders();

        // This is necessary according to section 10.4.2 of the HTTP/1.1 specification.
        header('WWW-Authenticate: Custom');
    }
}
