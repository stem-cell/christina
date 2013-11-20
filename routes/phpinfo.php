<?php namespace Christina;

// Shows PHP info.
Routes::$get['phpinfo'] = function($params)
{
    phpinfo();
};
