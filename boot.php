<?php namespace Christina;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/engine/utils.php';

requireDir(__DIR__.'/engine');

Logic::perform();
