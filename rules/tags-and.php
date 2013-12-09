<?php namespace Christina;

// Rule for matching tags in the most conventional fashion: "tag1 tag2 tag3".
class TagsAndRule extends Rule
{
    // You should pass an array of tags like ['tag1', 'tag2', 'tag3'].
    protected $type = 'array';

    // Remember, patterns are just for a single query command.
    static protected $pattern = '(?<name>[^-~:][^:]*)';

    function check(Post $post)
    {
        $checks = [];

        foreach ($this->data as $tag)
        {
            $check = $post->hasTag($tag);
            $checks[] = xnor($check, $this::$mode); // Logical biconditional for negation mode.
        }

        return allAreTrue($checks);
    }

    // We need to implement this because the constructor accepts arrays and not strings.
    static function fromMatch($match)
    {
        $rule = get_called_class();
        return new $rule([$match['name']]);
    }
}
