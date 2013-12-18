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

// Returns something with HTML entities and encoded with utf-8.
function html($text)
{
    return htmlentities($text, ENT_QUOTES, 'utf-8');
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
// And if the date is falsey (e.g., null), an appropriate text will be returned.
function humanDate($datetime, $noDateText = 'never')
{
    if (!$datetime) return $noDateText;

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

// Filters an array by calling a method on all its objects and
// checking if it returns true.
function filterByMethod($array, $methodName)
{
    return array_filter($array, function($i) use ($methodName) {
        return $i->$methodName();
    });
}

// Sorts an array by a property of all its objects.
// You know, I was thinking about http://stackoverflow.com/questions/1462503
// and it occurred to me that this solution is better for a couple of reasons.
function sortByProp($array, $propName, $reverse = false, $sortFlags = SORT_REGULAR)
{
    $sorted = [];

    foreach ($array as $item)
    {
        $sorted[$item->$propName][] = $item;
    }

    if ($reverse) krsort($sorted); else ksort($sorted);
    $result = [];

    foreach ($sorted as $subArray) foreach ($subArray as $item)
    {
        $result[] = $item;
    }

    return $result;
}

// Returns 'yes' or 'no' from a value by truthyness rules.
function yesNo($bool)
{
    return $bool ? 'yes' : 'no';
}

// Returns a reference to a static function.
// Why do we need shit like this?? PHP, get your shit together for fuck's sake.
// No, seriously PHP, you try and try to drive me nuts but see? I kick your ass in the end.
// You motherfucker son of a bitch language made by sadomasochist assholes.
function staticFunctionReference($name)
{
    return function() use ($name)
    {
        $className = strstr($name, '::', true);
        if (class_exists(__NAMESPACE__."\\$className")) $name = __NAMESPACE__."\\$name";
        return call_user_func_array($name, func_get_args());
    };
}

// Efficiently calculates how many digits the integer portion of a number has.
function digits($number)
{
    // Yes, I could convert to string and count the characters,
    // but this is faster and cooler.
    $log = log10($number);
    if ($log < 0) return 1;
    return floor($log) + 1;
}

// Formats a number to a minimum amount of digits.
// In other words, makes sure that a number has at least $digits on it, even if
// that means introducing redundant decimal zeroes at the end, or rounding the
// ones present exceeding the $digits count when combined with the integers.
// For example:
//     formatToMinimumDigits(10)           // 10.0
//     formatToMinimumDigits(1.1)          // 1.10
//     formatToMinimumDigits(12.34)        // 12.3
//     formatToMinimumDigits(1.234)        // 1.23
//     formatToMinimumDigits(1.203)        // 1.20
//     formatToMinimumDigits(123.4)        // 123
//     formatToMinimumDigits(100)          // 100
//     formatToMinimumDigits(1000)         // 1000
//     formatToMinimumDigits(1)            // 1.00
//     formatToMinimumDigits(1.002)        // 1.00
//     formatToMinimumDigits(1.005)        // 1.01
//     formatToMinimumDigits(1.005, false) // 1.00
// This is primarily useful for generating human-friendly numbers.
function formatToMinimumDigits($value, $round = true, $digits = 3)
{
    $integers = floor($value);

    $decimalsNeeded = $digits - digits($integers);

    if ($decimalsNeeded < 1)
    {
        return $integers;
    }
    else
    {
        if ($round)
        {
            // This relies on implicit type casting of float to string.
            $parts = explode('.', round($value, $decimalsNeeded));
            // We re-declare the integers because they may change
            // after we round the number.
            $integers = $parts[0];
        }
        else
        {
            // Again, implicit type cast to string.
            $parts = explode('.', $value);
        }
        
        // And because of the implicit type cast, we must guard against
        // 1.00 becoming 1, thus not exploding the second half of it.
        $decimals = isset($parts[1]) ? $parts[1] : '0';
        $joined = "$integers.$decimals".str_repeat('0', $digits);
        return substr($joined, 0, $digits + 1);
    }
}

// Returns a size in a human-readable form from a byte count.
function humanSize($bytes)
{
    if ($bytes < 1024) return "$bytes Bytes";

    $units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    foreach ($units as $i => $unit)
    {
        // The reason for this threshold is to avoid e.g., "1000 KB",
        // instead jumping from e.g., "999 KB" to "0.97 MB".
        $multiplier = pow(1024, $i + 1);
        $threshold = $multiplier * 1000;

        if ($bytes < $threshold)
        {
            $size = formatToMinimumDigits($bytes / $multiplier, false);
            return "$size $unit";
        }
    }
}

// Generates a pluralized string of an integer count.
function plural($count, $name, $pluralName = null)
{
    if (abs($count) == 1) return "$count $name";
    if (!$pluralName) $pluralName = "{$name}s";
    return "$count $pluralName";
}
