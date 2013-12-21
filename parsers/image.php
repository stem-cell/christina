<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Image handler parameters - simple format of /image/name[.ext]
Routes::$param['image'] = function($params)
{
    $re = '~^(?<name>[a-zA-Z0-9_-]+)(\.(?<ext>jpe?g|png|gif))?$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'name' => $matches['name'],
            'ext' => isset($matches['ext']) ? normalizeExt($matches['ext']) : null
        ];
    }
    else
    {
        throw new ParserException();
    }
};
