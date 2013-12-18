<?php namespace Christina;

// This class abstracts a post note.
class Note
{
    // IDs that represent this note, its post and owner in the database.
    public $id;
    public $postId;
    public $userId;

    // Datetimes when the note was created and last updated.
    // Notice that even if the note was never edited, its updated date will
    // be present (and equal to the creation date)
    public $created;
    public $updated;

    // Body of the note, that is, the note's text (or markup, at any rate).
    public $body;

    // A value indicating whether the note is active.
    public $isActive;

    // Note's version, starting from one.
    public $version;

    // Size and position of the note in relationship to the post.
    public $x;
    public $y;
    public $width;
    public $height;

    // Caches for the post and user objects returned from ->post() and ->user().
    private $post;
    private $user;

    // Builds a note from an appropriate database row construct.
    function __construct($data)
    {
        $this->id       = $data['id'];
        $this->postId   = $data['post_id'];
        $this->userId   = $data['user_id'];
        $this->created  = new \DateTime($data['created_at']);
        $this->updated  = new \DateTime($data['updated_at']);
        $this->body     = $data['body'];
        $this->isActive = !!$data['is_active'];
        $this->version  = $data['version'];
        $this->x        = $data['x'];
        $this->y        = $data['y'];
        $this->width    = $data['width'];
        $this->height   = $data['height'];
    }

    // Returns an object representing the Post where this note lives.
    function post()
    {
        return Cache::variable($this->post, function()
        {
            return Posts::get($this->postId);
        });
    }

    // Returns an object representing the User that wrote this note.
    function user()
    {
        return Cache::variable($this->user, function()
        {
            return Users::byId($this->userId);
        });
    }

    // Builds the note by fetching it from the database by the $id given.
    static function fromId($id)
    {
        $data = DB::object('noteById', $id);
        if (!$data) return null;
        $data = (array)$data;
        $data['id'] = $id;
        return new Note($data);
    }
}
