<?php namespace Christina;

// Represents an user avatar.
class Avatar
{
    // Avatar URL base.
    const urlBase = '/data/avatars/';

    // Reference to this avatar's user.
    public $user;

    // ID of the post that contains this avatar.
    public $postId;

    // Timestamp that the avatar was set to.
    public $timestamp;

    // Avatar URL.
    public $url;

    // Extended metadata cache.
    private $metadata;

    // Build from an user, post ID and avatar timestamp.
    function __construct(&$user, $postId, $timestamp)
    {
        $this->user = &$user;
        $this->postId = $postId;
        $this->timestamp = $timestamp;
        $this->url = Avatar::urlBase.$user->id.".jpg?$timestamp";
    }

    // Loads extended metadata, if it hasn't been loaded yet.
    function loadMetadata()
    {
        if (!$this->metadata) $this->metadata = (array)DB::object('avatarMetadataByUserId', $id);
    }

    // Returns a representation of the Avatar object that can be sent over the wire.
    function representation()
    {
        return [
            'url' => $this->url,
            'postId' => $this->postId
        ];
    }
}
