<?php namespace Christina;

class Blacklists
{
    // Gets the blacklist for a user as an array. No tags are prefixed, except for rating:N.
    // If $userId is not a positive integer, it will return the default (guest) blacklist.
    function get($userId)
    {
        $id = intval($userId);
        
        if ($id < 1)
        {
            $blacklist = (new Moebooru_Config())->default_blacklists;
        }
        else
        {
            $pdo = DB::connect();
            $query = $pdo->query("SELECT tags FROM user_blacklisted_tags WHERE user_id = $id");
            $rows = $query->fetchAll(PDO::FETCH_NUM);
            $blacklist = preg_split("/\r\n|\n|\r/", $rows[0][0]);
        }

        $blacklist = preg_replace('/\b(?!rating:)[a-z]+:/i', '', $blacklist);

        if (count($blacklist) == 1 and $blacklist[0] == '') return array();

        return $blacklist;
    }

    // Given a list of tags and a blacklist, see if the post is blacklisted.
    function check($tags, $blacklist)
    {
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