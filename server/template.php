<?php namespace Christina;

// Groups template-related functionality
class Template
{
    // Renders a template with the given variables.
    static function render($name, $vars)
    {
        extract($vars);
        require dirname(__DIR__)."/templates/$name.php";
    }
}