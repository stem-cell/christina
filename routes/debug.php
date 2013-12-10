<?php namespace Christina;

// Debug route, just for testing stuff.
Routes::$get['debug'] = function($params)
{
    echo 'Nothing is being debugged at the moment.';
};
