<?php namespace Christina;

foreach (   $post->circleTags() as $tag) echo Template::render('post-tag', compact('tag'));
foreach (   $post->artistTags() as $tag) echo Template::render('post-tag', compact('tag'));
foreach ($post->copyrightTags() as $tag) echo Template::render('post-tag', compact('tag'));
foreach ($post->characterTags() as $tag) echo Template::render('post-tag', compact('tag'));
foreach (  $post->generalTags() as $tag) echo Template::render('post-tag', compact('tag'));
foreach (    $post->faultTags() as $tag) echo Template::render('post-tag', compact('tag'));
