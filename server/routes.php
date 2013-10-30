<?php namespace Christina;

// This file makes direct use of routing.php; that
// file defined rules and this one follows them.

// Shows a post.
Routes::$get['post'] = function($params)
{

};

// Shows a list of feature support information.
Routes::$get['features'] = function($params)
{
    Template::display('features');
};

// Shows PHP info.
Routes::$get['phpinfo'] = function($params)
{

};
