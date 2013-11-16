<?php namespace Christina;

// This class deals with handling CSS files.
class CSS
{
    // Gets a CSS file's contents as a string.
    static function contents($name)
    {
        return file_get_contents(self::location($name));
    }

    // Gets the location for a named CSS file.
    static function location($name)
    {
        return dirname(__DIR__)."/css/$name.css";
    }

    // Gets an URL for a CSS file (based on current christina.phar location).
    static function url($name)
    {
        return $_SERVER['SCRIPT_NAME']."?css/$name.css";
    }

    // Echoes an HTML link to the given CSS file by name (for use in templates).
    static function link($name)
    {
        $url = self::url($name);
        echo "<link rel=\"stylesheet\" href=\"$url\" />";
    }
}
