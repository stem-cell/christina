<?php namespace Christina;

// Rule for matching rating of a post: "rating:s" or "rating:safe".
class RatingIsRule extends Rule
{
    // You should pass a string such as 'explicit', 'q', etc.
    protected $type = 'string';

    // Remember, patterns are just for a single query command.
    static protected $pattern = 'rating:(?<rating>s|safe|q|questionable|e|explicit)';

    function check(Post $post)
    {
        // We just need to check the first character.
        $ratingMatches = $post->rating[0] === $this->data[0];
        return xnor($this::$mode, $ratingMatches); // Allow negation.
    }
}
