<?php namespace Christina;

// Function to include a script inside the Phar.
// Note that the name does not require the .php extension.
// If it starts with "../", it is considered a path relative to the Phar.
function using($name)
{
    if (substr($name, 0, 3) == '../')
    {
        require_once Environment::resolve($name);
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

// Replaces the first instance of a pattern.
// Taken from: http://stackoverflow.com/a/2606638/124119
function str_replace_first($search, $replace, $subject)
{
    $pos = strpos($subject, $search);

    if ($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

// Performs require_once over an entire directory.
// Taken from: http://stackoverflow.com/a/2692394/124119
function requireDir($path)
{
    $dir      = new \RecursiveDirectoryIterator($path);
    $iterator = new \RecursiveIteratorIterator($dir);
    
    foreach ($iterator as $file)
    {
        $fname = $file->getFilename();

        if (preg_match('/\.php$/i', $fname))
        {
            require_once $file->getPathname();
        }
    }
}

// Converts a datetime to ISO 8601 format (used by JavaScript for example).
function isoDate($datetime)
{
    // We accept a string/timestamp representation or a proper datetime.
    if (!($datetime instanceof \DateTime)) $datetime = new \DateTime($datetime);
    return $datetime->format('c');
}

// Converts a datetime to a format readable by a human.
// Yes, the ISO format is readable but the idea is to have something even more readable.
function humanDate($datetime)
{
    // We accept a string/timestamp representation or a proper datetime.
    if (!($datetime instanceof \DateTime)) $datetime = new \DateTime($datetime);

    // Currently we're not doing as much as we'd like to.
    return $datetime->format("Y-m-d H:i:s");
}

// Returns true if the variable is of a particular type or class.
function is($typeOrClass, $var)
{
    $type = gettype($var);

    if ($type === 'object')
    {
        if (!is_a($var, $typeOrClass))
        {
            return false;
        }
    }
    else
    {
        if ($type !== $typeOrClass)
        {
            return false;
        }
    }

    return true;
}

// Checks if all values in an array evaluate to true.
function allAreTrue(array $array)
{
    foreach ($array as $value)
    {
        if (!$value) return false;
    }

    return true;
}

// Returns true if one, and only one, of the items in the array is true.
function oneIsTrue($array)
{
    $trues = 0; // Count of truthy values.

    foreach ($array as $value)
    {
        if ($value) $trues++;
    }

    return $trues === 1;
}

// Gets all subclasses of a class.
// Taken from http://stackoverflow.com/a/3470032/124119
function getSubclassesOf($parent)
{
    $result = array();

    foreach (get_declared_classes() as $class)
    {
        if (is_subclass_of($class, $parent)) $result[] = $class;
    }

    return $result;
}

// Logical biconditional, or XNOR (eXclusive Not OR).
// I'm just making this a function because it's more clear than
// writing an equality check.
// In plain english: if a, then b. if not a, then the opposite of b.
function xnor($a, $b)
{
    return !!$a === !!$b;
}

// Converts someStringLikeThis to some-string-like-this.
function camelToDashes($string)
{
    return strtolower(preg_replace('/[A-Z]/', '-$0', $string));
}

// Ensures that a variable is of a specific type or class.
// For example: ensure('string', $type);
// (for obvious reasons we're not including that in the function).
function ensure($type, $var)
{
    if (!is($type, $var)) throw TypeException();
}
