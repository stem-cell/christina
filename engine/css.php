<?php namespace Christina;

// This class deals with handling CSS files.
class CSS
{
    // Link to the CSS for the webfonts we are currently using.
    const fonts = '//fonts.googleapis.com/css?family=Ubuntu:300,400,700,300italic,400italic';

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
        return Routes::url("css/$name-$crc32.$ext");
    }

    // Echoes an HTML link to the given CSS file by name (for use in templates).
    // If $target is an not a valid name, it will be used as-is (e.g., an URL).
    static function link($target)
    {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $target))
        {
            $target = CSS::url($target);
        }

        echo "<link rel=\"stylesheet\" href=\"$target\">";
    }

    static function crc32($name)
    {
        return hash_file('crc32b', CSS::location($name, true));
    }
}
