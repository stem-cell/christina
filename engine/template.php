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

    // Helper function to format and output a page.
    static function page($name, $title, $vars = [])
    {
        $vars['title'] = "$title - Christina";
        $vars['contents'] = Template::render("$name-body", $vars);
        $vars['css'] = ['normalize', $name, CSS::fonts];
        echo Template::render('boilerplate', $vars);
    }

    // Helper function to format and return a simple table.
    // If only one argument is passed, no title will be added.
    static function table($title, $rows = null)
    {
        $result = '';

        if ($rows !== null) // In other words, if two arguments were passed...
        {
            $result .= "<h3>$title</h3>";
        }
        else
        {
            $rows = $title; // Swap in case of one argument.
        }

        $result .= Template::render('table', compact('rows'));

        return $result;
    }

    // Helper function to format and return an inline representation of an user.
    static function inlineUser($user, $default = 'nobody')
    {
        if (!$user) return $default;

        return Template::render('user-inline', compact('user'));
    }

    // Helper function to format and return a tag group.
    static function tagGroup($tags, $title)
    {
        return Template::render('post-tag-group', compact('tags', 'title'));
    }
}
