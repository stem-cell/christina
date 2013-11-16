<?php namespace Christina;

// This file contains parameter handlers.
// Look at routing.php to understand what is that about.

// Parameter parsing for serving CSS files. The regex should explain it.
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
        throw new \Exception();
    }
};
