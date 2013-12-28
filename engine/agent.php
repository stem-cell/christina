<?php namespace Christina;

// This class handles understanding user-agent capabilities and properties.
class Agent
{
    // Path to use as directory cache for phpbrowsecap.
    // Must be relative to the top-level christina.php script.
    const cacheDirectory = '../tmp';

    // Indicates whether the user-agent detection code has been initialized.
    private static $initialized = false;

    // Browscap object cache.
    private static $browseCap;

    // Gets the raw data for the user agent.
    static function get()
    {
        Agent::init();

        $bc = Cache::variable(Agent::$browseCap, function() {
            return new \phpbrowscap\Browscap(Agent::cacheDir());
        });

        return $bc->getBrowser();
    }

    // Returns the cache directory (to be) used by phpbrowsecap.
    static function cacheDir()
    {
        return normalizePath(CHRISTINA_ROOT.'/'.Agent::cacheDirectory);
    }

    // Loads the PHP browser capabilities library, if that hasn't been done already.
    static function init()
    {
        if (!Agent::$initialized)
        {
            lib('phpbrowscap/browscap');
            Agent::$initialized = true;
        }
    }
}
