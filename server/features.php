<?php namespace Christina;

// Feature support abstractions.
class Features
{
    // Checks if APC is installed.
    static function apc()
    {
        return extension_loaded('apc') and ini_get('apc.enabled');
    }

    // Checks if Memcache is installed.
    static function memcache()
    {
        return class_exists('Memcache');
    }

    // Checks if Memcached is installed.
    static function memcached()
    {
        return class_exists('Memcached');
    }
}