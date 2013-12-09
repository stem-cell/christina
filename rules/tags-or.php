<?php namespace Christina;

// Rule for matching one of the tags in a set: "~either_this ~or_this".
class TagsOrRule extends Rule
{
    // You should pass an array of tags like ['tag1', 'tag2', 'tag3'].
    protected $type = 'array';

    // Remember, patterns are just for a single query command.
    static protected $pattern = '~(?<name>[^:]*)';

    function check(Post $post)
    {
        $checks = [];

        foreach ($this->data as $tag)
        {
            $checks[] = $post->hasTag($tag);
        }

        return oneIsTrue($checks);
    }

    // We need to implement this because the constructor accepts arrays and not strings.
    static function fromMatch($match)
    {
        $rule = get_called_class();
        return new $rule([$match['name']]);
    }
}
