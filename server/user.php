<?php namespace Christina;

// Abstracts user information access.
class User
{
    // Gets information for the logged-in user. Returns null on failure.
    // Upon success, the result contains id, name, level and email.
    static function info()
    {
        $id = Session::get('user_id');

        if ($id)
        {
            return User::byId($id);
        }
        else
        {
            return null;
        }
    }

    // Gets an user's information by ID, caching as appropriate.
    // Upon success, the result contains id, name, level and email.
    static function byId($id)
    {
        if (isset($GLOBALS["christina-user-$id"]))
        {
            return $GLOBALS["christina-user-$id"];
        }
        else
        {
            $user = DB::object('userById', $id);
            $user['id'] = $id;
            return $GLOBALS["christina-user-$id"] = $user;
        }
    }

    // The following functions use the helper below to easily
    // retrieve specific user data by shorthands.
    // If no ID is specified, it reads the current user's info,
    // and if also the user isn't logged in, null is returned.
    static function prop($name, $id)
    {
        $info = $id ? User::byId($id) : User::info();
        if (!$info) return null;
        else return $info[$name];
    }

    // Gets the user's name.
    static function name($id = null)
    {
        return User::prop('name', $id);
    }

    // Gets the user's level.
    static function level($id = null)
    {
        return User::prop('level', $id);
    }

    // Gets the user's e-mail.
    static function email($id = null)
    {
        return User::prop('email', $id);
    }
}
