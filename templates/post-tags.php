<?php namespace Christina;

if ($tags =    $post->circleTags()) echo Template::tagGroup($tags, 'Circle');
if ($tags =    $post->artistTags()) echo Template::tagGroup($tags, 'Artist');
if ($tags = $post->copyrightTags()) echo Template::tagGroup($tags, 'Copyright');
if ($tags = $post->characterTags()) echo Template::tagGroup($tags, 'Character');
if ($tags =     $post->faultTags()) echo Template::tagGroup($tags, 'Faults');
if ($tags =   $post->generalTags()) echo Template::tagGroup($tags, 'Tags');

