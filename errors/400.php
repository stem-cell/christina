<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 400 Bad Request error.
class Http400Error extends HttpError
{
    const code = 400;

    const name = 'Bad Request';

    public $description = 'The request seems to be malformed or invalid.';
}
