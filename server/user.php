<?php namespace Christina;

// Abstracts user information access.
class User
{
    // Gets information for the logged-in user. Returns null on failure.
    // Upon success, the result contains id, name, password_hash, level and email.
    static function info()
    {
        $id = Session::get('user_id');

        if ($id)
        {
            $user = DB::object('userById', $id);
            $user['id'] = $id;
            return $user;
        }
        else
        {
            return null;
        }
    }
}