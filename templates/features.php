<?php namespace Christina;

$vars['title'] = 'Feature Detection Information - Christina';

$vars['contents'] = Template::render('features-body', []);

$vars['css'] = ['normalize', 'features', CSS::fonts];

Template::display('boilerplate', $vars);
