<?php namespace Christina;

// Rule for excluding tags: "-tag1 -tag2 -tag3".
// As in TagsAndRule, pass an array like ['tag1', 'tag2', 'tag3'].
class TagsNotRule extends TagsAndRule
{
    static protected $mode = false;

    static protected $pattern = '-(?<name>[^:]*)';
}
