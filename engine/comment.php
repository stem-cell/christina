<?php namespace Christina;

// This class abstracts a post comment.
class Comment
{
    // IDs that represent this comment, its post and owner in the database.
    public $id;
    public $postId;
    public $userId;

    // Datetimes when the comment was created and last updated.
    // Note that even if the comment was never edited, its updated date will
    // be present (and equal to the creation date)
    public $created;
    public $updated;

    // Body of the comment, that is, the comment text (or markup, at any rate).
    public $body;

    // A value indicating whether the comment was identified as spam.
    public $isSpam;

    // Caches for the post and user objects returned from ->post() and ->user().
    private $post;
    private $user;

    // Builds a comment from an appropriate database row construct.
    function __construct($data)
    {
        $this->id      = $data['id'];
        $this->postId  = $data['post_id'];
        $this->userId  = $data['user_id'];
        $this->created = new \DateTime($data['created_at']);
        $this->updated = new \DateTime($data['updated_at']);
        $this->body    = $data['body'];
        $this->isSpam  = !!$data['is_spam'];
    }

    // Returns an object representing the Post where this comment lives.
    function post()
    {
        return Cache::variable($this->post, function()
        {
            return Posts::get($this->postId);
        });
    }

    // Returns an object representing the User that posted this comment.
    function user()
    {
        return Cache::variable($this->user, function()
        {
            return Users::byId($this->userId);
        });
    }

    // Builds the comment by fetching it from the database by the $id given.
    static function fromId($id)
    {
        $data = DB::object('commentById', $id);
        if (!$data) return null;
        $data = (array)$data;
        $data['id'] = $id;
        return new Comment($data);
    }
}
