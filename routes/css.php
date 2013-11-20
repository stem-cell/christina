<?php namespace Christina;

// Serves a CSS file.
Routes::$get['css'] = function($params)
{
    header('content-type: text/css; charset=utf-8');
    if ($params['crc32']) Response::someThingsNeverChange();
    echo CSS::contents($params['name'], $params['min']);
};
