<?php namespace Christina;

// Serves an image file.
Routes::$get['image'] = function($params)
{
    $name = $params['name'];
    $ext = $params['ext'];

    if ($ext)
    {
        $name = "$name.$ext";
    }
    else
    {
        Images::addExtension($name);
    }

    Images::show($name);
};
