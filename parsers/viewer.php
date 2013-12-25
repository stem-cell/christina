<?php namespace Christina;

// This file contains a parameter handler.
// Look at engine/routes.php to understand what is that about.

// Post viewer parameters - simple format of /viewer/[123].
// Note that it's also capable of handling the viewer form's POST data.
Routes::$param['viewer'] = function($params)
{
    $re = '~^(?<id>[1-9][0-9]*)?$~';

    if (isset($_POST['id']) and ctype_digit($_POST['id']) and intval($_POST['id']) > 0)
    {
        return [
            'id' => intval($_POST['id'])
        ];
    }
    else if (preg_match($re, $params, $matches))
    {
        return
        [
            'id' => isset($matches['id']) ? $matches['id'] : null
        ];
    }
    else
    {
        throw new ParserException();
    }
};
