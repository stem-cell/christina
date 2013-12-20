<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 501 Not Implemented error.
class Http501Error extends HttpError
{
    const code = 501;

    const name = 'Not Implemented';

    function parse($params = [])
    {
        if (isset($params['feature']))
        {
            $feature = $params['feature'];
        }
        else
        {
            $feature = 'requested feature';
        }

        $this->description = "The $feature was not implemented by our lazy-ass developer.";
    }
}
