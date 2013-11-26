<?php namespace Christina;

// Shows a post.
Routes::$get['post'] = function($params)
{
    if ($params['json'])
    {
        Posts::showJson($params['id'], $params['min']);
    }
    else if ($params['html'])
    {
        Posts::showHtml($params['id']);
    }
    else
    {
        Posts::showImage($params['id']);
    }
};
