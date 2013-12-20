<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 404 Not Found error.
class Http404Error extends HttpError
{
    const code = 404;

    const name = 'Not Found';

    public $description = 'The resource you requested was not found.';

    function parse($params = [])
    {
        if (isset($params['message']))
        {
            $this->description = $params['message'];
        }
        else if (isset($params['resource']))
        {
            $this->description = "The $params[resource] was not found.";
        }
    }
}
