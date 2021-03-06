<?php namespace Christina;

// Handles dealing with the environment Christina is running on.
class Environment
{
    // Configuration cache.
    private static $config;

    // Resolves a path relative to the christina.php script.
    static function resolve($path)
    {
        $base = dirname($_SERVER['SCRIPT_FILENAME']);
        return normalizePath("$base/$path");
    }

    // Gets the configuration used by MyImoutoBooru.
    static function config()
    {
        return Cache::variable(Environment::$config, function() {
            using('../config/default_config.php');
            using('../config/config.php');
            return new \Moebooru_Config();
        });
    }

    // Returns the signature used by the server.
    static function signature()
    {
        return $_SERVER['SERVER_SIGNATURE'];
    }
}
