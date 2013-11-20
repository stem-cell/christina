<?php namespace Christina;

// Shows a list of feature support information.
Routes::$get['features'] = function($params)
{
    Template::display('features');
};
