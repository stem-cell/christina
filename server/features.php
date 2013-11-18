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

    // Gets an array of the availability of all server-side features by name.
    static function serverSide()
    {
        return
        [
            'Alternative PHP Cache' => Features::apc(),
            'Memcache' => Features::memcache(),
            'Memcached' => Features::memcached(),
            'Ranting about PHP' => defined('T_PAAMAYIM_NEKUDOTAYIM')
        ];
    }
}