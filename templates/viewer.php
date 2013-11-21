<?php namespace Christina;

$vars['title'] = 'Post Viewer - Christina';

$vars['contents'] = Template::render('viewer-body', []);

$vars['css'] = ['normalize', 'viewer', CSS::fonts];

Template::display('boilerplate', $vars);
