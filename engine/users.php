<?php namespace Christina;

// Abstracts user information access.
class Users
{
    // Gets information for the logged-in user. Returns null on failure.
    // Upon success, the result contains id, name, level and email.
    static function current()
    {
        $id = Session::get('user_id');

        if ($id)
        {
            return Users::byId($id);
        }
        else
        {
            return null;
        }
    }

    // Gets an User by ID.
    static function byId($id)
    {
        return Cache::runtime("christina-user-$id", function() use ($id) {
            return new User($id);
        });
    }

    // The following functions use the helper below to easily
    // retrieve specific user data by shorthands.
    // If no ID is specified, it reads the current user's info,
    // and if also the user isn't logged in, null is returned.
    static function prop($prop, $id = null)
    {
        $user = $id ? Users::byId($id) : Users::current();
        if (!$user) return null;
        else return $user->$prop;
    }

    // Gets the user's name.
    static function name($id = null)
    {
        return Users::prop('name', $id);
    }

    // Gets the user's level.
    static function level($id = null)
    {
        return Users::prop('level', $id);
    }

    // Gets the user's e-mail.
    static function email($id = null)
    {
        return Users::prop('email', $id);
    }

    // Gets the current user's ID. Of course, this takes no parameters.
    static function id()
    {
        return Session::get('user_id');
    }
}
