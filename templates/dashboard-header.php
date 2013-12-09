<?php namespace Christina;

if (!Users::current())
{
    $text = '(Not logged in)';
}
else
{
    $text = 'Logged in as <span>'.Users::name().'</name>';
}

echo "<h2>$text</h2>";
