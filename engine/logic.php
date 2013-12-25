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
        try
        {
            Logic::init();
            Routes::handle();
        }
        catch (UnimplementedException $e)
        {
            Errors::show(501, ['feature' => $e->feature]);
        }
        catch (NotFoundException $e)
        {
            Errors::show(404, ['resource' => $e->resource]);
        }
        catch (\Exception $e)
        {
            Errors::show(500);
        }
    }

    // Bootstrap anything that's necessary.
    static function init()
    {
        if (Logic::$initialized) return;
        
        Rules::init();

        Logic::$initialized = true;
    }
}
