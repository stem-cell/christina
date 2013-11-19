<?php namespace Christina;

$vars['title'] = 'Dashboard - Christina';

$vars['contents'] = Template::render('dashboard-body', []);

$vars['css'] = ['normalize', 'dashboard', CSS::fonts];

Template::display('boilerplate', $vars);
