<?php namespace Christina;

require_once __DIR__.'/engine/minifier.php';

// Configuration.
$rootFiles = 'boot.php'; // Regular expression.
$rootFolders = 'engine|images|templates|libs|css|routes|parsers|rules'; // Regular expression.
$pharFile = __DIR__.'/release/christina.phar'; // Output file.
$stub = __DIR__.'/phar-stub.php'; // Phar stub.
$debug = false; // On debug mode, files are not minified.
$compression = 'gzip'; // You can choose 'gzip', 'bzip2', or 'none'.

if (!\Phar::canWrite())
{
    echo "Your current PHP settings do not allow the creation of a Phar archive.\n";
    echo "Visit the following URL for more information: http://php.net/phar.readonly\n";
    exit;
}

// Files added will be represented by dots appended to this, thus the lack of newline.
echo 'Creating Phar archive';

// Check if there's any Phar already present. If there is, we will copy its file
// permissions to the one we generate instead of setting our default "-rwxr-xr-x".
$permissions = 0755;
if (file_exists($pharFile))
{
    $permissions = fileperms($pharFile) & 0777;
}

// We have to delete the currently-present phar, because otherwise old files remain.
if (file_exists($pharFile)) unlink ($pharFile);

$phar = new \Phar($pharFile);

// Recursively iterate iterating on the recursive iteration iterator iteratively. 
$dir = new \RecursiveDirectoryIterator(__DIR__, \RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new \RecursiveIteratorIterator($dir);

foreach ($iterator as $filename => $file)
{
    $relative = strtr(substr($filename, strlen(__DIR__) + 1), '\\', '/');

    $valid = "~^($rootFiles|($rootFolders)/.*)\$~";
    if (!preg_match($valid, $relative)) continue;

    //$phar->addFile($filename, $relative);
    $content = file_get_contents($filename);

    if (substr($filename, -4) == '.php' and !$debug)
    {
        $content = Minify::php($content);
    }
    
    $phar->addFromString($relative, $content);
    
    $modes = array('gzip' =>\Phar::GZ, 'bzip2' => \Phar::BZ2);

    if (isset($modes[$compression]))
    {
        $phar[$relative]->compress($modes[$compression]);
    }
    
    echo '.';
}

echo ' ';

$phar->setStub(Minify::php(file_get_contents($stub)));

// Make executable on Unix.
chmod($pharFile, $permissions);

echo "Done.\n";
