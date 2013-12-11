<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Post route parameters - simple format of /post/123[.ext]
// (For "json" or "html", a special format should be served).
Routes::$param['post'] = function($params)
{
    $re = '~^(?<id>[0-9]+)(\.(?<ext>(min\.)?json|html?|jpe?g|png|gif))?$~';

    if (preg_match($re, $params, $matches))
    {
        $ext = isset($matches['ext']) ? $matches['ext'] : null;
        return
        [
            'id' => $matches['id'],
            'ext' => $ext,
            'json' => $ext and substr($ext, -4) === 'json',
            'html' => $ext and substr($ext, 0, 3) === 'htm',
            'min' => $ext and substr($ext, 0, 3) === 'min'
        ];
    }
    else
    {
        throw new ParserException();
    }
};
