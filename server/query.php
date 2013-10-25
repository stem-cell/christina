<?php namespace Christina;

// Abstract interpretations of the query that called our scripts.
class Query
{
    // Is the query a valid post query?
    function isPost()
    {
        return isset($_GET['post']) and filter_var($_GET['post'], FILTER_VALIDATE_INT) !== false;
    }

    // Is the query asking for JSON?
    function isJson()
    {
        return isset($_GET['format']) and $_GET['format'] == 'json';
    }
}
