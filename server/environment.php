<?php namespace Christina;

// Handles dealing with the environment Christina is running on.
class Environment
{
    // Resolves a path relative to the christina.php script.
    static function resolve($path)
    {
        $base = dirname($_SERVER['SCRIPT_FILENAME']);
        return normalizePath("$base/$path");
    }

    // Gets the configuration used by MyImoutoBooru.
    static function config()
    {
        using('../config/default_config.php');
        using('../config/config.php');
        return new \Moebooru_Config();
    }
}
