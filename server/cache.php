<?php namespace Christina;

// By the way, that SfYaml library is slow as fuck. I'm telling you, that thing
// is a bottleneck for performance.
// And the fact that PHP sucks means we have to call it at each request. But,
// maybe the server has APC installed, in which case we can only call it once and
// cache the result. It should be much faster for each subsequent request.
// So first of all: is APC installed?
$isThatThingInstalled = extension_loaded('apc') and ini_get('apc.enabled');

// We also want to be able to know on the live site.
if (isset($_GET['apc']))
{
    echo 'APC installed and enabled: '.($isThatThingInstalled ? 'yes' : 'no');
    exit;
}

// For completeness' sake, there's also memcache/d, even thought we currently don't have code paths that use them:
if (isset($_GET['memcache']))
{
    echo 'Memcache installed: '.(class_exists('Memcache') ? 'yes' : 'no');
    exit;
}

if (isset($_GET['memcached']))
{
    echo 'Memcached installed: '.(class_exists('Memcached') ? 'yes' : 'no');
    exit;
}

// There might be other caches (such as XCache, I believe), so let's throw this in too:
if (isset($_GET['phpinfo']))
{
    phpinfo(); exit;
}

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
