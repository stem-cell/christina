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

// Alignments, used by the class below.
define('ALIGN_LEFT'  , 1);
define('ALIGN_RIGHT' , 2);
define('ALIGN_CENTER', 4);

// This class implements a cross-platform "line editor" for command-line output.
// On Linux you can do lots of fancy stuff in the command-line, but not on Windows.
// To overcome this limitation, you can restrict yourself to just editing the
// current line, by means of echoing backspaces.
// However, while on Windows you can echo an infinite number of backspaces and
// it will only clear the current line, on Linux this will wipe previous lines, too.
// The result can obviously be very messy.
// Thus, this utility will handle remembering how many backspaces are needed,
// echoing them, preventing newlines and such, so that you can only focus on
// what you want to output. I might also port it to other languages later.
class CommandLiner
{
    // Where to output.
    public $target = STDOUT;

    // How many columns are available in each line (most commandlines support at least 80).
    // I'm using 79 because, I'm not sure if some console will put the cursor on the next
    // line and screw with my backspacing or what.
    // Note: this value will be auto-set later with a reasonably cross platform guess.
    // I'm making it public because it may be useful to be able to just read it.
    public $columns = 79;

    // How many characters have been output (in order to be able to delete them later).
    private $characters = 0;

    // A value indicating whether the instance has been closed.
    private $closed = false;

    // Constructs an instance while being able to specify the output target.
    // The column width may be specified; if not, it will try to guess it on Windows
    // and most Unix OSes.
    function __construct($target = STDOUT, $columns = null)
    {
        $this->columns = $columns ?: CommandLiner::crossPlatformConsoleWidth() - 1;
        $this->target = $target;
    }

    // Clears the currently-outputted text from the command-line.
    function clear()
    {
        if ($this->closed) throw new \Exception("CommandLiner instance close()d");

        // We do it like this because the backspace seemingly doesn't clear text.
        // In other words, we back up, fill with spaces, and back up again.
        $text = str_repeat("\x08", $this->characters)
              . str_repeat(" " , $this->characters)
              . str_repeat("\x08", $this->characters);
        $this->output($text);

        $this->characters = 0;
    }

    // Writes a line to the output, optionally specifying alignment (default is left-align).
    // Whatever was present in the line will be erased before writing this.
    function write($content, $alignment = ALIGN_LEFT)
    {
        if ($this->closed) throw new \Exception("CommandLiner instance close()d");

        $clean = CommandLiner::clean($content);
        $clean = CommandLiner::ellipsis($clean, $this->columns);
        $length = strlen($clean);

        // We're optimizing for the least amount of characters being printed.
        switch ($alignment)
        {
            case ALIGN_LEFT:
                // First case, left alignment, involves backspacing to the line start,
                // writing our string, filling the rest with spaces, and backspacing them.
                $extra = max(0, $this->characters - $length);
                $text = str_repeat("\x08", $this->characters)
                      . $clean
                      . str_repeat(' ', $extra)
                      . str_repeat("\x08", $extra);
                $chars = $length;
                break;

            case ALIGN_RIGHT:
            case ALIGN_CENTER:
                // Second and third case, right alignment, involves backspacing to the
                // line start, and writing a left-padded version of the input.
                // The third case (center alignment) could be optimized, but honestly...
                // I'm not even sure I'd need center alignment :P
                $padded = CommandLiner::fit($clean, $this->columns, $alignment);
                $text = str_repeat("\x08", $this->characters) . $padded;
                $chars = strlen($padded);
                break;

            default:
                $msg = '$alignment must be ALIGN_LEFT, ALIGN_RIGHT or ALIGN_CENTER';
                throw new \Exception($msg);
                break;
        }

        $this->characters = $chars;
        $this->output($text);
    }

    // Closes the instance, echoing a newline and prohibiting future write() or clear() calls.
    function close()
    {
        echo "\n";
        $this->closed = true;
    }

    // Outputs text to the command-line.
    private function output($text)
    {
        fwrite($this->target, $text);
    }

    // Fits text in a given horizontal space with the specified alignment.
    static function fit($text, $columns, $alignment = ALIGN_LEFT, $padding = ' ')
    {
        $text = CommandLiner::clean($text);
        $length = strlen($text);

        if ($length < $columns)
        {
            $padType = [
                ALIGN_LEFT   => STR_PAD_RIGHT,
                ALIGN_RIGHT  => STR_PAD_LEFT,
                ALIGN_CENTER => STR_PAD_BOTH
            ];

            $result = str_pad($text, $columns, $padding, $padType[$alignment]);
        }
        else
        {
            $result = CommandLiner::ellipsis($text, $columns);
        }

        return $result;
    }

    // Cleans the text so it can be backspaced later.
    // For example, it cleans it from newlines (and other control characters).
    // Furthermore, the output is trimmed.
    static function clean($text)
    {
        return trim(preg_replace('/[\x00-\x1F\x7F]/', '', $text));
    }

    // Crops and applies ellipsis to the $text if it doesn't fit in the $columns.
    static function ellipsis($text, $columns)
    {
        $length = strlen($text);
        if (!$columns) return '';
        if ($columns === 1) return $text ? $text[0] : '.';
        if ($columns === 2 and $length > $columns) return '..';
        if ($columns === 3 and $length > $columns) return '...';
        if ($length > $columns) return substr($text, 0, $columns - 3) . '...';
        return $text;
    }

    // Gets the console width in columns, cross-platform (tested on Windows and Linux).
    static function crossPlatformConsoleWidth()
    {
        // Different logic for Windows and Unix.
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0)
        {
            preg_match('/---+(\n[^|]+?){2}(?<cols>\d+)/', `mode`, $matches);
            return $matches['cols'];
        }
        else
        {
            // On Unix, we just check if tput is available.
            // Both tput and which should be present on any system that isn't Windows.
            if (`which tput`)
            {
                return `tput cols`;
            }
            else
            {
                // Sensible default.
                return 80;
            }
        }
    }
}

// Here begins code that uses the above classes.

try
{
    $builder = new PharBuilder(__DIR__.'/release/christina.phar');
    $liner = new CommandLiner();
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

$prefix1 = 'Creating Phar archive...';
$prefix2 = 'Phar archive created.';
$prefixSpace = 25;
$percentageSpace = 8;
$progressBarSpace = max(2, $liner->columns - ($prefixSpace + $percentageSpace));
$prefix = $liner::fit($prefix1, $prefixSpace);
$progress = '[' . $liner::fit('calculating...', $progressBarSpace - 2, ALIGN_CENTER) . ']';
$percentage = $liner::fit('0.00%', $percentageSpace, ALIGN_RIGHT);

$liner->write("$prefix$progress$percentage");

$progressCallback = function($done, $total)
{
    global $liner, $prefix, $progressBarSpace, $percentageSpace;

    $percentageNumber = $done * 100 / $total;
    $percentageFormatted = sprintf('%0.2f', round($percentageNumber, 2)) . '%';
    $percentage = $liner::fit($percentageFormatted, $percentageSpace, ALIGN_RIGHT);

    // Building the progressbar is a bit more involved.
    $barSpace = $progressBarSpace - 2;
    $filledCount = (int)round($percentageNumber * $barSpace / 100);
    $emptyCount = $barSpace - $filledCount;
    $progress = '[' . str_repeat('=', $filledCount) . str_repeat(' ', $emptyCount) . ']';

    $liner->write("$prefix$progress$percentage");
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

$prefix = $liner::fit($prefix2, $prefixSpace);
$progress = '[' . str_repeat('=', $progressBarSpace - 2) . ']';
$percentage = $liner::fit('100.00%', $percentageSpace, ALIGN_RIGHT);
$liner->write("$prefix$progress$percentage");
$liner->close();
