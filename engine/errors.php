<?php namespace Christina;

// This class contains helpers to issue errors to the user in a friendly manner.
class Errors
{
    // A value indicating whether errors have been initialized.
    static $initialized = false;

    // Loads all errors.
    static function init()
    {
        if (!Errors::$initialized)
        {
            $base = dirname(__DIR__);
            requireDir("$base/errors");
            Errors::$initialized = true;
        }
    }

    // Gets an instance of the class describing the given error code, with
    // an optional parameters object that would be passed to the error constructor.
    static function get($code, $params = [])
    {
        Errors::init();
        $className = "Christina\\Http{$code}Error";
        if (class_exists($className)) return new $className($params);
        throw new UnimplementedException("HTTP $code error page");
        return Errors::get(501, ['feature' => "HTTP $code error page"]);
    }

    // Shows an error to the user, with optional parameters.
    static function show($code, $params = [])
    {
        $error = Errors::get($code, $params);
        $error->setHeaders();
        Template::display('error', compact('error'));
    }
}

// Base class for HTTP errors.
abstract class HttpError
{
    // HTTP code for this error.
    const code = 724;

    // HTTP name of this error.
    const name = 'This Line Should Be Unreachable';

    // Description of this error message.
    public $description = 'The developer was too lazy to provide an error message.';

    // Storage for any parameters passed to the constructor.
    private $params = [];

    // The error constructor accepts an optional parameters array.
    function __construct($params = [])
    {
        $this->params = $params;
        $this->parse($params);
    }

    // Override this function if you plan on running special logic
    // on the constructor's parameters.
    function parse($params = []) {}

    // This function sets appropriate headers for this error code.
    // If a particular error requires special HTTP headers, this
    // method should be overridden to provide such functionality.
    function setHeaders()
    {
        http_response_code($this::code);
    }
}
