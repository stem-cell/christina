<?php namespace Christina;

// This file contains parameter handlers.
// Look at routing.php to understand what is that about.

Routes::$param['css'] = function($params)
{
    $re = '~^(?<name>[a-zA-Z0-9-_]+)(-(?<mtime>[0-9]+))?((?<min>\.min)?\.css)?$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'name' => $matches['name'],
            'mtime' => isset($matches['mtime']) ? $matches['mtime'] : 0,
            'min' => isset($matches['min'])
        ];
    }
    else
    {
        throw new \Exception();
    }
};
