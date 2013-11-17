<?php namespace Christina;

// This class deals with handling CSS files.
class CSS
{
    // Gets a CSS file's contents as a string.
    static function contents($name, $min = true)
    {
        return file_get_contents(CSS::location($name, $min));
    }

    // Gets the location for a named CSS file.
    static function location($name, $min = true)
    {
        $ext = $min ? 'min.css' : 'css';
        return dirname(__DIR__)."/css/$name.$ext";
    }

    // Gets an URL for a CSS file (based on current christina.phar location).
    static function url($name, $min = true)
    {
        $crc32 = CSS::crc32($name);
        $ext = $min ? 'min.css' : 'css';
        return $_SERVER['SCRIPT_NAME']."?css/$name-$crc32.$ext";
    }

    // Echoes an HTML link to the given CSS file by name (for use in templates).
    static function link($name)
    {
        $url = CSS::url($name);
        echo "<link rel=\"stylesheet\" href=\"$url\">";
    }

    static function crc32($name)
    {
        return hash_file('crc32b', CSS::location($name, true));
    }
}
