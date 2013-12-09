<?php namespace Christina;

// Abstracts access to Christina's own images repository.
class Images
{
    // Returns the contents of the named image.
    // Common file extensions (.png, .jpg and .gif) may be omitted.
    static function get($name)
    {
        $base = dirname(__DIR__)."/images/$name";
        if (file_exists("$base.png")) return file_get_contents("$base.png");
        if (file_exists("$base.jpg")) return file_get_contents("$base.jpg");
        if (file_exists("$base.gif")) return file_get_contents("$base.gif");
        return file_get_contents("$base");
    }
}
