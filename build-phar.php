<?php namespace Christina;

// This script may exit with positive status codes on error:
// 1: Started but failed mid-way.
// 2: Could not even start.
// In case of an error a message is shown (to stdout).

// Also note that since PHP is a crappy hack of a language, error messages
// might also (or instead) be emitted as E_NOTICES, be written into log files,
// be sent by e-mail, be posted on Usenet, be screamed by a feminist on PMS,
// or be sent to a GCC compiler in a croudsourced attempt to eventually build Skynet.

// Builds Phar archives from files and directories.
// I'm encapsulating this because it's so cool and I may want to use it in the future.
class PharBuilder
{
    // Default permissions for phar archives (Unix format, where 0755
    // is "write/read/execute for owner, read/execute for others").
    public $defaultPermissions = 0755;

    // Whether or not we're in debug mode.
    // In debug mode, files are not minified (and line numbers are preserved).
    public $debug = false;

    // Compression used. You can choose "gzip", "bzip2", or "none".
    // An invalid value defaults to "none".
    public $compression = 'gzip';

    // Whether or not to force clean build mode.
    public $force = false;

    // Array of files to add.
    // The items may be relative paths, or pairs mapping relative paths from
    // the Phar's root to relative/absolute paths in the filesystem.
    // For example: ['foo/bar.baz', 'baz/bar.foo' =>'/home/foo/bar.baz'].
    // Note that this order is better than the opposite because it allows
    // for duplicates in the Path sourced from the same filesystem object.
    // Relative paths will be stored in the same relative hierarchy.
    public $files = [];

    // Array of directories to add.
    // Same rules as the ->$files array.
    // Absolute folders will map in the most intuitive way, which is to take
    // the folder's relative contents and put them inside the given target.
    public $dirs = [];

    // Phar file stub (entry point code file).
    // This is PHP code that will bootstrap the Phar, and must start with
    // the regular PHP-opening token (<?php...)
    // For an example, see http://php.net/phar.createdefaultstub
    public $stub = null;

    // Path to the Phar file.
    private $pharPath = '';

    // Phar object.
    private $phar;

    // This array will map between relative paths to the
    // root of the Phar file into fully-qualified file paths
    // from the local filesystem. Example:
    // ['foo/bar.baz' => '/home/john/project/foo/bar.baz', /* ... */]
    // It is built by ->buildMap().
    private $fileMap = [];

    // This maps local (file) paths in the Phar to their respective MD5 hashes.
    // For example: ['foo/bar.baz' => '1a2b3c...', /* ... */].
    private $hashMap = [];

    // Constructs a PharBuilder from a path to a Phar file.
    // If the file exists, it will use update mode.
    // Otherwise, it will use clean build mode.
    // Throws BadMethodCallException if called twice,
    // and UnexpectedValueException if the phar archive can't be opened.
    function __construct($pharPath)
    {
        $this->pharPath = $pharPath;
        $this->phar = new \Phar($pharPath);
    }

    // Starts the build process.
    // Provide a callback, and it will be called with progress information,
    // in the form: function($processedBytes, $totalBytes).
    function build(callable $callback = null)
    {
        // Sanity check.
        if (!\Phar::canWrite())
        {
            $msg = "Your current PHP settings do not allow Phar archive creation."
                 . "\nVisit this URL for more info: http://php.net/phar.readonly";
            throw new \PharException($msg);
        }

        if ($this->force and file_exists($this->pharPath))
        {
            $success = unlink($this->pharPath);

            if (!$success)
            {
                throw new \Exception('Phar file could not be deleted for clean rebuild');
            }
        }

        if (file_exists($this->pharPath))
        {
            // Permissions taken and kept from the original Phar.
            $permissions = fileperms($this->pharPath) & 0777;
        }
        else
        {
            $permissions = $this->defaultPermissions;
        }

        $this->buildFileMap();
        $this->buildHashMap();

        $step = function($data) use ($callback)
        {
            if (!$callback) return;

            $callback($data['done'], $data['total']);
        };

        if (file_exists($this->pharPath))
        {
            $this->update($step);
        }
        else
        {
            $this->cleanBuild($step);
        }

        $this->setMetadata();

        $this->setStub();

        // Set permissions.
        chmod($this->pharPath, $permissions);
    }

    // Performs a clean build.
    private function cleanBuild($callback)
    {
        $total = $this->getTotalBytes();
        $done = 0;

        foreach ($this->fileMap as $relative => $absolute)
        {
            $this->setFileFromFilesystem($relative, $absolute);
            $done += filesize($absolute);

            @$callback(compact('done', 'total'));
        }
    }

    // Performs an in-place update.
    private function update($callback)
    {
        $total = $this->getTotalBytes();
        $done = 0;

        foreach ($this->filesToDelete() as $path)
        {
            $size = $this->phar[$path]->getCompressedSize();
            $this->deleteFile($path);
            $done += $size;

            @$callback(compact('done', 'total'));
        }

        foreach ($this->filesToSet() as $localPath => $filesystemPath)
        {
            $this->setFileFromFilesystem($localPath, $filesystemPath);
            $done += filesize($filesystemPath);

            @$callback(compact('done', 'total'));
        }
    }

    // Builds the ->$fileMap, and returns it too (for convenience).
    private function buildFileMap()
    {
        $map = [];

        // Build the recursive directory paths.
        foreach ($this->dirs as $relative => $absolute)
        {
            // For non-associative indexes.
            if (is_int($relative)) $relative = $absolute;

            $absolute = realpath($absolute);

            if (!is_dir($absolute)) throw new \Exception("No directory \"$absolute\" found.");

            $flags = \RecursiveDirectoryIterator::SKIP_DOTS;
            $dir = new \RecursiveDirectoryIterator($absolute, $flags);
            $iterator = new \RecursiveIteratorIterator($dir);

            foreach ($iterator as $pathname => $fileinfo)
            {
                // We only want files.
                if (!$fileinfo->isFile()) continue;

                // Unreadable files should throw an error.
                if (!$fileinfo->isReadable()) throw new \Exception("\"$pathname\" unreadable.");

                $local = PharBuilder::fixSeparators(substr($pathname, strlen($absolute) + 1));
                $map["$relative/$local"] = $pathname;
            }
        }

        // Build the simple file paths.
        foreach ($this->files as $relative => $absolute)
        {
            // For non-associative indexes.
            if (is_int($relative)) $relative = $absolute;

            $absolute = realpath($absolute);

            if (!is_file($absolute)) throw new \Exception("No file \"$absolute\" found.");

            // Unreadable files should throw an error.
            if (!is_readable($absolute)) throw new \Exception("\"$absolute\" unreadable.");

            $normalized = PharBuilder::fixSeparators($relative);
            $map[$normalized] = $absolute;
        }

        return $this->fileMap = $map;
    }

    // Builds the ->$hashMap, and returns it too (for convenience).
    // Depends on the ->$fileMap being properly built.
    private function buildHashMap()
    {
        // First, we'll build a map from filesystem paths to MD5s.
        // The reason for this is that a file might be added twice
        // under different (local) names, and doing it like this
        // we will only need to hash it once.
        $fsHashMap = [];

        foreach ($this->fileMap as $path)
        {
            // Only hash each file once.
            if (isset($fsHashMap[$path])) continue;

            $md5 = md5_file($path);
            $fsHashMap[$path] = $md5;
        }

        // Now build the actual hash map.
        $localHashMap = [];

        foreach ($this->fileMap as $localPath => $fsPath)
        {
            $md5 = $fsHashMap[$fsPath];
            $localHashMap[$localPath] = $md5;
        }

        return $this->hashMap = $localHashMap;
    }

    // Sets the Phar's stub, if any.
    private function setStub()
    {
        if ($this->stub)
        {
            // Minify and set.
            $stub = PharBuilder::minifyPhp($this->stub);
            $this->phar->setStub($stub);
        }
        else
        {
            // No stub.
            $this->phar->setStub('');
        }
    }

    // Set a file's contents in the Phar archive
    // (or add a new one with the given contents).
    private function setFile($localPath, $contents)
    {
        $this->phar->addFromString($localPath, $contents);

        // Compress according to the chosen mode.
        $modes = ['gzip' => \Phar::GZ, 'bzip2' => \Phar::BZ2];
        if (isset($modes[$this->compression]))
        {
            $mode = $modes[$this->compression];

            if (\Phar::canCompress($mode))
            {
                $this->phar[$localPath]->compress($mode);
            }
        }
    }

    // Set a file's contents in the Phar archive from a filesystem file.
    private function setFileFromFilesystem($localPath, $filesystemPath)
    {
        $content = file_get_contents($filesystemPath);

        if (substr($filesystemPath, -4) == '.php' and !$this->debug)
        {
            $content = PharBuilder::minifyPhp($content);
        }

        $this->setFile($localPath, $content);
    }

    // Deletes a file in the Phar archive.
    private function deleteFile($path)
    {
        $this->phar->delete($path);
    }

    // Sets the Phar archive's metadata.
    // We use it for build purposes.
    private function setMetadata()
    {
        // Clean-up. I'm not sure it's needed, but just in case...
        if ($this->phar->hasMetadata()) $this->phar->delMetadata();

        $metadata = [
            'hashMap' => $this->hashMap
        ];

        $this->phar->setMetadata($metadata);
    }

    // Gets the Phar archive's metadata.
    // Defaults to a mock metadata if one isn't found.
    private function getMetadata()
    {
        if ($this->phar->hasMetadata())
        {
            return $this->phar->getMetadata();
        }
        else
        {
            return [
                'hashMap' => []
            ];
        }
    }

    // Gets the total number of bytes that need to be added to,
    // replaced in or deleted from the Phar archive.
    // Does not take into account the Phar stub or metadata.
    private function getTotalBytes()
    {
        $total = 0;

        foreach ($this->filesToDelete() as $path)
        {
            $total += $this->phar[$path]->getCompressedSize();
        }

        foreach ($this->filesToSet() as $path)
        {
            $total += filesize($path);
        }

        return $total;
    }

    // Gets the list of files that have to be deleted from the archive,
    // as an array of archive paths. Example: ['foo.bar', 'foo/bar.baz'].
    private function filesToDelete()
    {
        $list = [];
        $metadata = $this->getMetadata();

        foreach ($metadata['hashMap'] as $relativePath => $md5)
        {
            if (!isset($this->hashMap[$relativePath]))
            {
                $list[] = $relativePath;
            }
        }

        return $list;
    }

    // Gets the list of files that have to be add/replace into the archive,
    // as a map of archive paths into filesystem paths. Example:
    // ['foo/bar.baz' => '/home/john/project/foo/bar.baz', /* ... */].
    private function filesToSet()
    {
        $list = [];
        $metadata = $this->getMetadata();

        foreach ($this->hashMap as $relativePath => $md5)
        {
            if (!isset($metadata['hashMap'][$relativePath])
            or $metadata['hashMap'][$relativePath] !== $md5)
            {
                $absolutePath = $this->fileMap[$relativePath];
                $list[$relativePath] = $absolutePath;
            }
        }

        return $list;
    }

    // Utility to fix stupid file path separators (namely on Windows).
    static private function fixSeparators($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }

    // PHP minifier. Why? Well, as Cave Johnson once said,
    // science isn't about *why* - it's about *why not*.
    static private function minifyPhp($code)
    {
        $result = '';
        $skip = false;

        foreach (token_get_all($code) as $token)
        {
            if ($skip)
            {
                $skip = false;
            }
            else if (is_string($token))
            {
                $result .= $token;
            }
            else
            {
                switch ($token[0])
                {
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                    case T_END_HEREDOC:
                        $result .= $token[1].";\n";
                        $skip = true;
                        break;
                    case T_WHITESPACE:
                        $result .= ' ';
                        break;
                    default:
                        $result .= $token[1];
                }
            }
        }

        return $result;
    }
}

// Here begins code that uses the above class.

try
{
    $builder = new PharBuilder(__DIR__.'/release/christina.phar');
}
catch (\UnexpectedValueException $e)
{
    $msg = $e->getMessage();
    echo "Fuck! Could not start Phar build/update because... $msg\n";
    exit(2);
}

$builder->files = [
    'boot.php'
];

$builder->dirs = [
    'engine',
    'images',
    'templates',
    'libs',
    'css',
    'routes',
    'parsers',
    'rules',
    'sql',
    'errors'
];

$builder->stub = file_get_contents(__DIR__.'/phar-stub.php');

echo 'Creating Phar archive';

$progressSteps = 10; // How many files should be processed for each dot to be output.
$progress = 0; // counter for the dot output (which depends on the progressSteps).

$progressCallback = function($done, $total)
{
    global $progressSteps, $progress;
    if ($progress++ % $progressSteps === 0) echo '.';
};

try
{
    $builder->build($progressCallback);
}
catch (\Exception $e)
{
    $msg = $e->getMessage();
    $type = get_class($e);
    echo "There was a problem of type \"$type\" during the build process: $msg\n";
    exit(1);
}

echo " Done.\n";
