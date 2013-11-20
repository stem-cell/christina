<?php namespace Christina;

// Shows a post.
Routes::$get['post'] = function($params)
{
    Posts::show($params['id'], $params['json']);
};
