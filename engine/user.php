<?php namespace Christina;

// Object-oriented instance of an user.
class User
{
    // User ID in the database.
    public $id;

    // Username.
    public $name;

    // User level. Defined in Environment->config()->user_levels. Defaults are:
    // [ "Unactivated" =>  0 ,
    //   "Blocked"     => 10 ,
    //   "Member"      => 20 ,
    //   "Privileged"  => 30 ,
    //   "Contributor" => 33 ,
    //   "Janitor"     => 35 ,
    //   "Mod"         => 40 ,
    //   "Admin"       => 50 ]
    public $level;

    // User's e-mail.
    public $email;

    // User's password hash.
    public $passHash;

    // User's avatar (instance of Avatar class).
    public $avatar;

    // Extended metadata cache.
    private $metadata;

    // Build an user from an ID.
    function __construct($id)
    {
        $user = DB::object('userById', $id);
        $this->id = $id;
        $this->name = $user['name'];
        $this->level = $user['level'];
        $this->email = $user['email'];
        $this->passHash = $user['password_hash'];
        $avatarTimestamp = strtotime($user['avatar_timestamp']);
        $this->avatar = new Avatar($this, $user['avatar_post_id'], $avatarTimestamp);
    }

    // Extended metadata about the user.
    function metadata()
    {
        Cache::variable($this->metadata, function() {
            return (array)DB::object('userMetadataById', $this->id);
        });
    }

    // Returns an array with public data about the user.
    // This data is for example what might be sent as JSON publicly.
    // In other words, this data should only include a basic public profile.
    function publicData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'avatar' => $this->avatar->representation(),
        ];
    }

    // Returns a link to the user's profile URL.
    function profileUrl()
    {
        return "/user/show/{$this->id}";
    }
}
