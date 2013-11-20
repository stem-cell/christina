<?php namespace Christina;

// Abstracts any YAML libraries in use.
class Yaml
{
    // A value indicating whether the init() function has been called already.
    static $initialized = false;

    // Parses the contents of a YAML file.
    static function parse($path)
    {
        Yaml::init();
        return \Symfony\Yaml\Yaml::parse($path);
    }

    // Loads the YAML library, if it hasn't been done already.
    static function init()
    {
        if (!Yaml::$initialized)
        {
            // We really shouldn't trust SfYaml to be present in a specific
            // folder on the server-side, since as we saw with the latest
            // update, that's pretty fragile. So, now we're including it here.
            lib('symfony/yaml');
            Yaml::$initialized = true;
        }
    }
}
