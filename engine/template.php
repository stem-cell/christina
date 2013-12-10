<?php namespace Christina;

// Groups template-related functionality.
class Template
{
    // Renders a template with the given variables, and
    // returns its rendered result as a string.
    static function render($templateName, $templateVars = [])
    {
        extract($templateVars);
        ob_start();
        require dirname(__DIR__)."/templates/$templateName.php";
        return ob_get_clean();
    }

    // Minify and display rendered results. Wraps Template::render().
    static function display($name, $vars = [])
    {
        echo Minify::html(Template::render($name, $vars));
    }
}