<?php namespace Christina;

// Configuration.
$rootFiles = ''; // Regular expression.
$rootFolders = ''; // Regular expression.
$pharFile = __DIR__.'/christina.phar';
$stub = __DIR__.'/phar-stub.php'

if (!\Phar::canWrite()) {
    echo "Your current PHP settings do not allow the creation of a Phar archive.\n";
    echo "Visit the following URL for more information: http://php.net/phar.readonly\n";
    exit;
}

echo "Creating Phar archive...\n";

// Check if there's any Phar already present. If there is, we will copy its file
// permissions to the one we generate instead of setting our default "-rwxr-xr-x".
$permissions = 0755;
if (file_exists($pharFile)) {
    $permissions = fileperms($pharFile) & 0777;
}

$phar = new \Phar($pharFile);

// Recursively iterate iterating on the recursive iteration iterator iteratively. 
$dir = new \RecursiveDirectoryIterator(__DIR__, \RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new \RecursiveIteratorIterator($dir);

// Files added will be represented by dots appended to this, thus the lack of newline.
echo 'Adding files';

foreach ($iterator as $filename => $file) {
    $relative = strtr(substr($filename, strlen(__DIR__) + 1), '\\', '/');

    $valid = "~^($rootFiles|($rootFolders)/.*)\$~";
    if (!preg_match($valid, $relative)) continue;

    $phar->addFile($filename, $relative);
    $phar[$relative]->compress(\Phar::GZ); // We are gzipping the files.
    echo '.';
}

echo "\n";

$phar->setStub(file_get_contents($stub));

// Make executable on Unix.
chmod($pharFile, $permissions);

echo "Done.\n";
