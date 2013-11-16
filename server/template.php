<?php namespace Christina;

// Groups template-related functionality.
class Template
{
    // Renders a template with the given variables, and
    // returns its rendered result as a string.
    static function render($name, $vars = [])
    {
        extract($vars);
        ob_start();
        require dirname(__DIR__)."/templates/$name.php";
        return ob_get_clean();
    }

    // Minify and display rendered results. Wraps self::render().
    static function display($name, $vars = [])
    {
        echo Minify::html(self::render($name, $vars));
    }
}
