<?php namespace Christina;

// Shows the administrative dashboard.
Routes::$get['dashboard'] = function($params)
{
    Template::display('dashboard');
};
