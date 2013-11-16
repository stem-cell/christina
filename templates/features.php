<?php namespace Christina;

$vars['title'] = 'Feature Detection Information - Christina';

$vars['contents'] = Template::render('features-body', []);

$vars['css'] = ['test'];

Template::display('boilerplate', $vars);
