<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Post route parameters - simple format of /post/123[.ext]
// (if ext is "json", then a special format should be served).
Routes::$param['post'] = function($params)
{
    $re = '~^(?<id>[0-9]+)(\.(?<ext>[a-z]{3,4}))?$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'id' => $matches['id'],
            'ext' => isset($matches['ext']) ? $matches['ext'] : null,
            'json' => isset($matches['ext']) and $matches['ext'] === 'json'
        ];
    }
    else
    {
        throw new \Exception();
    }
};
