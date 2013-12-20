<?php namespace Christina;

// Displays error codes in a friendly manner.
Routes::$get['error'] = function($params)
{
    Errors::show($params['code']);
};
