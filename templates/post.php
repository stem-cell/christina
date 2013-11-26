<?php namespace Christina;

$id = $postInfo['post']['id'];

$vars['title'] = "Post #$id - Christina";

$vars['contents'] = Template::render('post-body', $postInfo);

$vars['css'] = ['normalize', 'post', CSS::fonts];

Template::display('boilerplate', $vars);
