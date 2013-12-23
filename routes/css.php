<?php namespace Christina;

// Serves a CSS file.
Routes::$get['css'] = function($params)
{
    Response::mimetype('css');
    if ($params['crc32']) Response::cache();
    echo CSS::contents($params['name'], $params['min']);
};
