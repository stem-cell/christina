#!/usr/bin/env php
<?php

$pharFilename = basename(__FILE__);
Phar::mapPhar($pharFilename);
require "phar://$pharFilename/boot.php";
__HALT_COMPILER();
