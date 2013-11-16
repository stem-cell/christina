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
}
