<?php namespace Christina;

$code = $error::code;

Template::page('error', "HTTP $code Error :(", compact('error'));
