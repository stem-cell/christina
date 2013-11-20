<?php namespace Christina;

if (!User::info())
{
    $text = '(Not logged in)';
}
else
{
    $text = 'Logged in as <span>'.User::name().'</name>';
}

echo "<h2>$text</h2>";
