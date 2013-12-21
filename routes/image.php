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

    if (Images::exists($name))
    {
        Response::mimetype(extensionFrom($name));
        echo Images::get($name);
    }
    else
    {
        throw new NotFoundException("image \"$name\"");
    }
};
