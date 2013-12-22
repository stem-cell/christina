<?php namespace Christina;

// This file contains an error abstraction.
// To understand its usage, head over to engine/errors.php.

// The 405 Method Not Allowed error.
class Http405Error extends HttpError
{
    const code = 405;

    const name = 'Method Not Allowed';

    const image = 'http-405';

    function parse($params = [])
    {
        $used = strtoupper(Request::method());

        if (isset($params['expected']))
        {
            $expected = strtoupper($params['expected']);
            $this->description = "The request should use HTTP $expected, not $used";
        }
        else
        {
            $this->description = "HTTP $used is not appropriate for this request.";
        }
    }
}
