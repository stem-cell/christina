<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 403 Forbidden error.
class Http403Error extends HttpError
{
    const code = 403;

    const name = 'Forbidden';

    public $description = 'Access to the requested resource has been denied.';
}
