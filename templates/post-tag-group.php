<?php namespace Christina;

if (substr($title, -1, 1) !== 's' and count($tags) > 1)
{
    $title .= 's';
}

echo "<h3>$title</h3><ul>";
foreach ($tags as $tag) echo Template::render('post-tag', compact('tag'));
echo '</ul>';
