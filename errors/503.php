<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 503 Service Unavailable error.
class Http503Error extends HttpError
{
    const code = 503;

    const name = 'Service Unavailable';

    const image = 'http-503';

    public $description = 'The server is currently unable to handle the request.';
}
