<?php namespace Christina;

// Function to include a script inside the Phar.
// Note that the name does not require the .php extension.
// If it starts with "../", it is considered a path relative to the Phar.
function using($name)
{
    if (substr($name, 0, 3) == '../')
    {
        require_once Enivronment::resolve($name);
    }
    else
    {
        require_once __DIR__."/$name.php";
    }
}

// Loads a library from the lib folder. Pass it a string like 'symfony/yaml'.
function lib($path)
{
    require_once dirname(__DIR__)."/libs/$path.php";
}

// Normalizes a path, handling .. references that go beyond the initial folder reference.
// Taken from here: http://www.php.net/manual/en/function.realpath.php#112367
function normalizePath($path)
{
    $parts = array(); // Array to build a new path from the good parts
    $path = str_replace('\\', '/', $path); // Replace backslashes with forwardslashes
    $path = preg_replace('/\/+/', '/', $path); // Combine multiple slashes into a single slash
    $segments = explode('/', $path); // Collect path segments
    $test = ''; // Initialize testing variable

    foreach($segments as $segment)
    {
        if($segment != '.')
        {
            $test = array_pop($parts);

            if(is_null($test))
            {
                $parts[] = $segment;
            }
            else if($segment == '..')
            {
                if($test == '..') $parts[] = $test;

                if($test == '..' || $test == '') $parts[] = $segment;
            }
            else
            {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode('/', $parts);
}

// Remove the phar url schema.
function unPhar($path)
{
    return preg_replace('~^phar://~', '', $path);
}

// Echo something with HTML entities and encoded with utf-8.
function html($text)
{
    echo htmlentities($text, ENT_QUOTES, 'utf-8');
}

function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
