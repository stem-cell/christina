<?php namespace Christina;

$pharFilename = basename(__FILE__);
\Phar::mapPhar($pharFilename);
require "phar://$pharFilename/server/boot.php";
__HALT_COMPILER();
