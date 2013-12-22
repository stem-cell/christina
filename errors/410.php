<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 410 Gone error.
class Http410Error extends HttpError
{
    const code = 410;

    const name = 'Gone';

    const image = 'http-410';

    function parse($params = [])
    {
        if (isset($params['resource']))
        {
            $resource = $params['resource'];
        }
        else
        {
            $resource = 'requested resource';
        }

        $this->description = "The $resource is not available anymore.";
    }
}
