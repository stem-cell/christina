<?php namespace Christina;

$vars['title'] = 'Feature Detection Information - Christina';

$vars['contents'] = Template::render('features-body', []);

$vars['css'] = ['features'];

Template::display('boilerplate', $vars);
