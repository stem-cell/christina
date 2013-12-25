<?php namespace Christina;

// An interactive post viewer.
Routes::$get['viewer'] = function($params)
{
    Template::display('viewer', $params);
};

// Redirector for the form input.
Routes::$post['viewer'] = function($params)
{
    $id = $params['id'];
    Routes::redirect("viewer/$id");
};
