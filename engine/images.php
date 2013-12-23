<?php namespace Christina;

// Abstracts access to Christina's own images repository.
class Images
{
    // Returns the contents of the named image.
    // Common file extensions (.png, .jpg and .gif) may be omitted.
    static function get($name)
    {
        $base = dirname(__DIR__)."/images";
        Images::addExtension($name);
        return file_get_contents("$base/$name");
    }

    // Gets the URL for an image hosted internally by Christina.
    static function url($name)
    {
        Images::addExtension($name);
        return Routes::url("image/$name");
    }

    // Adds the proper extension for a lone image filename.
    static function addExtension(&$name)
    {
        $base = dirname(__DIR__)."/images/$name";
        $extensions = ['png', 'jpg', 'gif'];

        if (!hasExtension($name, $extensions))
        {
            foreach ($extensions as $ext)
            {
                if (file_exists("$base.$ext")) return $name = "$name.$ext";
            }
        }

        return $name;
    }

    // Checks if an image exists. Does not auto-append an extension.
    static function exists($name)
    {
        return file_exists(dirname(__DIR__)."/images/$name");
    }

    // Shows an image.
    static function show($name)
    {
        Images::addExtension($name);

        if (Images::exists($name))
        {
            Response::mimetype(extensionFrom($name));
            Response::cache();
            echo Images::get($name);
        }
        else
        {
            throw new NotFoundException("image \"$name\"");
        }
    }
}
