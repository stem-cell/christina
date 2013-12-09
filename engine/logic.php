<?php namespace Christina;

// This file used to contain more code. And it was a mess.
// Now it's more of a wrapper for the entry point, our "main()".
// Isn't it nice when things are this clean?
class Logic
{
    // Have we called initialization logic yet?
    static $initialized = false;

    // Start Christina.
    static function perform()
    {
        Logic::init();

        if (Routes::isValid())
        {
            Routes::call();
        }
        else if (Request::isEmpty())
        {
            Routes::home();
        }
        else
        {
            Response::youProbablyDontKnowWhatYouAreDoing();
        }
    }

    // Bootstrap anything that's necessary.
    static function init()
    {
        if (Logic::$initialized) return;
        else Logic::$initialized = true;
        
        Rules::init();
    }
}
