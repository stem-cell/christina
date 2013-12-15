<?php namespace Christina;

// Post instance model. Convenience abstractions for data on posts,
// because using arrays for everything is as idiomatic as it is idiotic.
class Post
{
    // Raw data representing the post. Should match the general database
    // row structure expected from MyImoutoBooru.
    public $raw;

    // ID that represents the post in the database.
    public $id;

    // File extension.
    public $ext;

    // MD5 representing the post's file.
    public $md5;

    // Tags array cache.
    private $tags;

    // DateTimes related to the post.
    public $commented;
    public $noted;
    public $created;
    public $indexed;

    // Owner and approver (if any) of the post. Both are User objects.
    public $owner;
    public $approver;

    // Metadata about the post image.
    // Image object with size and url.
    public $image;

    // Sample image metadata, if any.
    // Image object (if present) with size and url.
    public $sample = null;

    // JPEG version image metadata, if any.
    // Image object (if present) with size and url.
    public $jpeg = null;

    // Thumbnail image metadata.
    // Image object with url, but not size.
    public $thumb;

    // Default "display" URL, that is, standard image to be shown.
    public $display;

    // Source of the post, if any was specified. Usually an URL.
    public $source = null;

    // Post's rating. 'safe|questionable|explicit'.
    public $rating;

    // Post's score.
    public $score;

    // A series of boolean values indicating whether or not the post...
    public $isShownInIndex;
    public $isHeld;
    public $hasChildren;
    public $isRatingLocked;
    public $isNoteLocked;

    // Post's status. I am only aware of "active" at the moment.
    public $status;

    // ID of the parent post (if any).
    public $parentId = null;

    // Build a post object from an array map got from a database row, or
    // a compatible construct.
    function __construct($data)
    {
        // Safety check.
        if (!$data) throw new ParameterException();

        $this->parseData((array)$data);

        // Build the tags array.
        $tags = Posts::getTags($data['id']);
    }

    // Store the raw data and initialize properties.
    private function parseData($data)
    {
        // Basic data.
        $this->id  = $data['id'];
        $this->ext = $data['file_ext'];
        $this->md5 = $data['md5'];

        // Dates.
        if ($date = $data['last_commented_at']) $this->commented = new \DateTime($date);
        if ($date = $data['last_noted_at'])     $this->noted     = new \DateTime($date);
        if ($date = $data['created_at'])        $this->created   = new \DateTime($date);
        if ($date = $data['index_timestamp'])   $this->indexed   = new \DateTime($date);

        // Users.
        $this->owner = new User($data['user_id']);
        $this->approver = $data['approver_id'] ? new User($data['approver_id']) : null;

        // Images.
        $this->setImage($data, $this->image, null, $data['file_size'], 'original');
        $this->setImage($data, $this->sample, 'sample', $data['sample_size'], 'sample');
        $this->setImage($data, $this->jpeg, 'jpeg', $data['jpeg_size'], 'jpeg');
        $this->setImage($data, $this->thumb, 'actual_preview', null, 'thumbnail');
        $this->display = Posts::imageUrl($this);

        // Post metadata.
        $this->source         = $data['source'];
        $this->rating         = Posts::expandRating($data['rating']);
        $this->score          = $data['score'];
        $this->isShownInIndex = !!$data['is_shown_in_index'];
        $this->isHeld         = !!$data['is_held'];
        $this->hasChildren    = !!$data['has_children'];
        $this->isRatingLocked = !!$data['is_rating_locked'];
        $this->isNoteLocked   = !!$data['is_note_locked'];
        $this->status         = $data['status'];
        $this->parentId       = $data['parent_id'];

        // Store raw data just in case.
        $this->raw = $data;
    }

    // Tags array, containing a list of tag objects.
    function tags()
    {
        return Cache::variable($this->tags, function() {
            return Posts::getTags($this->id);
        });
    }

    // Returns the post data into a format that can be sent over the wire.
    function representation()
    {
        $tags = array_map(function($i) { return $i->representation(); }, $this->tags());

        return [
            'id'             => $this->id,
            'display'        => $this->display,
            'created'        => $this->created   ? isoDate($this->created)   : null,
            'indexed'        => $this->indexed   ? isoDate($this->indexed)   : null,
            'commented'      => $this->commented ? isoDate($this->commented) : null,
            'noted'          => $this->noted     ? isoDate($this->noted)     : null,
            'owner'          => $this->owner->publicData(),
            'approver'       => $this->approver ? $this->approver->publicData()   : null,
            'image'          => $this->image->representation(),
            'thumb'          => $this->thumb->representation(),
            'sample'         => $this->sample ? $this->sample->representation() : null,
            'jpeg'           => $this->jpeg   ? $this->jpeg->representation()   : null,
            'source'         => $this->source,
            'rating'         => $this->rating,
            'score'          => $this->score,
            'isShownInIndex' => $this->isShownInIndex,
            'isHeld'         => $this->isHeld,
            'hasChildren'    => $this->hasChildren,
            'isRatingLocked' => $this->isRatingLocked,
            'isNoteLocked'   => $this->isNoteLocked,
            'status'         => $this->status,
            'parentId'       => $this->parentId,
            'tags'           => $tags,
            'blacklisted'    => Blacklists::check($this)
        ];
    }

    // Helper to set the Image objects.
    function setImage($data, &$var, $prefix, $size, $type)
    {
        $prefix = $prefix ? $prefix.'_' : '';
        $width  = $data[$prefix.'width'];
        $height = $data[$prefix.'height'];
        $url    = Posts::imageUrl($this, $type);
        if ($width) $var = new Image($width, $height, $size, $url);
    }

    // Checks if the post has a given tag, by name.
    // It also accepts the * wildcard (like 'tag*').
    function hasTag($name)
    {
        $pattern = str_replace('\\*', '.*', preg_quote($name, '/'));

        foreach ($this->tags() as $tag)
        {
            if (strpos($name, '*') !== false)
            {
                // Wildcard logic. Uses regular expressions.
                if (preg_match("/^$pattern$/i", $tag->name)) return true;
            }
            else
            {
                // Simple logic.
                if ($tag->name === $name) return true;
            }
        }

        return false;
    }
}
