<?php namespace Christina;

// Function to include a script inside the Phar.
// Note that the name does not require the .php extension.
function using($name)
{
    require_once __DIR__."/$name.php";
}
