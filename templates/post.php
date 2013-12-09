<?php namespace Christina;

$vars['title'] = "Post #{$post->id} - Christina";

$vars['contents'] = Template::render('post-body', compact('post'));

$vars['css'] = ['normalize', 'post', CSS::fonts];

Template::display('boilerplate', $vars);
