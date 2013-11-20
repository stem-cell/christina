<?php namespace Christina;

// This file used to contain more code. And it was a mess.
// Now it's more of a wrapper for the entry point, our "main()".
// Isn't it nice when things are this clean?
class Logic
{
    // Start Christina.
    static function perform()
    {
        if (Routes::isValid())
        {
            Routes::call();
        }
        else
        {
            Response::youProbablyDontKnowWhatYouAreDoing();
        }
    }
}
