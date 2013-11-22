<?php namespace Christina;

// Shows a post.
Routes::$get['post'] = function($params)
{
    if ($params['json'])
    {
        Posts::showJson($params['id'], $params['min']);
    }
    else
    {
        Posts::showImage($params['id']);
    }
};
