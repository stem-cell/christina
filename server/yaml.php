<?php namespace Christina;

// We really shouldn't trust SfYaml to be present in a specific folder on
// the server-side, since as we saw with the latest update, that's pretty
// fragile. So, now we're including it here.
lib('symfony/yaml');
using('../config/default_config.php');
using('../config/config.php');
