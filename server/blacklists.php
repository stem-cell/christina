<?php namespace Christina;

class Blacklists
{
    // Gets the blacklist for a user as an array. No tags are prefixed, except for rating:N.
    // If $userId is not a positive integer, it will return the default (guest) blacklist.
    static function get($userId)
    {
        $id = intval($userId);
        
        if ($id < 1)
        {
            $blacklist = (new \Moebooru_Config())->default_blacklists;
        }
        else
        {
            $rows = DB::rows('blacklistForUser', $id);
            $blacklist = preg_split("/\r\n|\n|\r/", $rows[0][0]);
        }

        $blacklist = preg_replace('/\b(?!rating:)[a-z]+:/i', '', $blacklist);

        if (count($blacklist) == 1 and $blacklist[0] == '') return array();

        return $blacklist;
    }

    // Given a list of tags and a blacklist, see if the post is blacklisted.
    // If no blacklist is specified, use the current user's. If the user isn't
    // logged in, use the default blacklist.
    static function check($tags, $blacklist = null)
    {
        if (!$blacklist)
        {
            $userId = User::id();
            $blacklist = Blacklists::get($userId);
        }

        foreach ($blacklist as $blacklisted)
        {
            foreach ($tags as $tag)
            {
                if ($tag == $blacklisted) return true;
            }
        }

        return false;
    }
}
