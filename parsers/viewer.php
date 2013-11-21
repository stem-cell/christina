<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Post viewer parameters - simple format of /viewer/[123]
Routes::$param['viewer'] = function($params)
{
    $re = '~^(?<id>[0-9]+)?$~';

    if (preg_match($re, $params, $matches))
    {
        return
        [
            'id' => isset($matches['id']) ? $matches['id'] : null,
        ];
    }
    else
    {
        throw new \Exception();
    }
};
