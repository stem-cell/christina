<?php namespace Christina;

// Again, just like with jQuery, we're taking advantage of the fact that,
// conveniently, MyImoutoBooru is already using tools we'd like to use anyway.
using('/../../vendor/Symfony/Component/Yaml/Yaml.php');
using('/../../vendor/Symfony/Component/Yaml/Parser.php');
using('/../../vendor/Symfony/Component/Yaml/Inline.php');
using('/../../config/default_config.php');
using('/../../config/config.php');
use Symfony\Component\Yaml\Yaml as SfYaml;
