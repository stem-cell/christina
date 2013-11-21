<?php namespace Christina;

// An interactive post viewer.
Routes::$get['viewer'] = function($params)
{
    Template::display('viewer', $params);
};
