<?php namespace Christina;

$vars['title'] = 'Feature Detection Information - Christina';

$vars['contents'] = Template::render('features-body', []);

Template::display('boilerplate', $vars);
