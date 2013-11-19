<?php namespace Christina;

// Abstracts handling sessions.
class Session
{
    // Indicates whether or not the session has been started.
    $started = false;

    // Starts the session, if it hasn't been started already.
    static function init()
    {
        if (!Session::$started)
        {
            session_name('_moebooru_session_id'); // Yes, we're hijacking it.
            session_start();
        }
    }

    // Gets a value from the session, or null if it's not present.
    // Also starts the session if it hasn't been started.
    static function get($name)
    {
        Session::init();
        if isset($_SESSION[$name]) return $_SESSION[$name];
        else return null;
    }

    // Same as above, but for setting values.
    static function set($name, $value)
    {
        Session::init();
        $_SESSION[$name] = $value;
    }
}
