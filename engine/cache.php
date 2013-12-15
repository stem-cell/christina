<?php namespace Christina;

// This class groups caching-related utilities.
// Mostly to abstract boring cache logic, which I seem to use fairly often.
class Cache
{
    // Caches the content for as long as possible, i.e., runtime lifecycle.
    // In the future we might want to implement Memecache/d, but for now
    // there's only a code path for APC.
    // It falls back to using the current PHP session as a storage.
    static function lifecycle($name, callable $fallback)
    {
        ensure('string', $name);
        $apc = extension_loaded('apc') and ini_get('apc.enabled');
        $name = "christina-lc-cache-$name";

        if ($apc)
        {
            if (apc_exists($name))
            {
                return apc_fetch($name);
            }
            else
            {
                $value = $fallback();
                apc_store($name, $value);
                return $value;
            }
        }
        else
        {
            // Better than no optimization at all.
            if (Session::exists($name))
            {
                return Session::get($name);
            }
            else
            {
                Session::set($name, $value = $fallback());
                return $value;
            }
        }
    }

    // Cache something throughout the script's execution time,
    // using the $GLOBALS superglobal.
    static function runtime($name, callable $fallback)
    {
        ensure('string', $name);
        $name = "christina-rt-cache-$name";

        return Cache::variable($GLOBALS[$name], $fallback);
    }

    // Cache using an existing variable, passed by reference.
    static function variable(&$var, callable $fallback)
    {
        if (isset($var))
        {
            return $var;
        }
        else
        {
            return $var = $fallback();
        }
    }
}
