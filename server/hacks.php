<?php namespace Christina;

// Naughty PHP needs some ass-pounding.

// There are several ways to cause an error. PHP knows each and
// every single one of them. One of the worst ways is what's called
// a Segmentation Fault. http://xkcd.com/371/
// PHP is pretty feature-complete when it comes to screwing up.
// One way it can sefault is because of a bad recursion limit on
// its Perl-compatible regular expressions. On Windows, the stack for
// regexps is 256 KB, while on Linux it's 8 MB. Let's set some sane
// limits, by limiting the recursion depth to stack size / 500.
if (strtolower(substr(PHP_OS, 0, 3)) === 'win')
{
    ini_set("pcre.recursion_limit", "524"); // Windows.
}
else
{
    ini_set("pcre.recursion_limit", "16777"); // Linux.
}
