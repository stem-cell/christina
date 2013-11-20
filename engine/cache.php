<?php namespace Christina;

// By the way, that SfYaml library is slow as fuck. I'm telling you, that thing
// is a bottleneck for performance.
// And the fact that PHP sucks means we have to call it at each request. But,
// maybe the server has APC installed, in which case we can only call it once and
// cache the result. It should be much faster for each subsequent request.

// And this is how we cache stuff with APC and a session variable fallback:
function cache($name, $fallback)
{
    $apc = extension_loaded('apc') and ini_get('apc.enabled');

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
