<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 500 Internal Server Error.
class Http500Error extends HttpError
{
    const code = 500;

    const name = 'Internal Server Error';

    public $description = 'An unexpected condition prevents fulfillment of the request.';
}
