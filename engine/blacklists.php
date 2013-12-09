<?php namespace Christina;

class Blacklists
{
    // Gets the blacklist for a user as an array of queries.
    // If $userId is not a positive integer, it will return the default (guest) blacklist.
    static function get($userId)
    {
        $id = intval($userId);
        
        if ($id < 1)
        {
            $blacklist = trim(Environment::config()->default_blacklists);
        }
        else
        {
            $blacklist = trim(DB::object('blacklistForUser', $id)['tags']);
        }

        if (!$blacklist) return [];

        $lines = preg_split("/\r\n|\n|\r/", $blacklist);

        $queries = array_map(function($i) { return new Query($i); }, $lines);

        return $queries;
    }

    // Given a list of tags and a blacklist, see if the post is blacklisted.
    // If no blacklist is specified, use the current user's. If the user isn't
    // logged in, use the default blacklist.
    static function check(Post $post, $blacklist = null)
    {
        if (!$blacklist)
        {
            $userId = Users::id();
            $blacklist = Blacklists::get($userId);
        }

        foreach ($blacklist as $query)
        {
            if ($query->check($post)) return true;
        }

        return false;
    }
}
