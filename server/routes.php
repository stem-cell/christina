<?php namespace Christina;

// This file makes direct use of routing.php; that
// file defined rules and this one follows them.

// Shows a post.
Routes::$get['post'] = function($params)
{
    Posts::show($params['id'], $params['json']);
};

// Shows a list of feature support information.
Routes::$get['features'] = function($params)
{
    Template::display('features');
};

// Shows PHP info.
Routes::$get['phpinfo'] = function($params)
{
    phpinfo();
};

// Serves a CSS file.
Routes::$get['css'] = function($params)
{
    header('content-type: text/css; charset=utf-8');
    if ($params['crc32']) Response::someThingsNeverChange();
    echo CSS::contents($params['name'], $params['min']);
};

// Shows the administrative dashboard.
Routes::$get['dashboard'] = function($params)
{
    Template::display('dashboard');
};
