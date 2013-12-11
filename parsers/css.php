<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Parameter parsing for serving CSS files.
// A route for css follows this general format:
// css/name[-1a2b3c4d][.min].css
// The CRC32 is relative to the minified file,
// and is required for the file to be cached.
Routes::$param['css'] = function($params)
{
    $re = '~^(?<name>[a-zA-Z0-9_]+)(-(?<crc32>[0-9a-f]+))?((?<min>\.min)?\.css)?$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'name' => $matches['name'],
            'crc32' => isset($matches['crc32']) ? $matches['crc32'] : null,
            'min' => isset($matches['min'])
        ];
    }
    else
    {
        throw new ParserException();
    }
};
