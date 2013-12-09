<?php namespace Christina;

// This class represents a tag search query.
// It's useful for example to parse blacklists.
class Query
{
    // The raw query, as passed when creating this object.
    public $raw;

    // The rules that define this query.
    // An array of Rule derivates.
    public $rules = [];
    
    // All commands that couldn't be parsed into any rule.
    public $incomprehensible = [];
    
    // Build a query from a query string.
    // A query string can be anything; it will be interpreted in a way
    // compatible to how MyImoutoBooru does (hopefully - I'm not going to copy
    // and paste MyImoutoBooru's horrible spaghetti code).
    function __construct($raw = '')
    {
        $this->raw = $raw;
        $items = preg_split("/\s+/", $raw); // Split by whitespace.

        $orTags = []; // Or-prefixed tags, like '~tag1 ~tag2'. Need to be processed together.
        $other  = []; // All the other commands. They all can be processed individually.

        foreach ($items as $item)
        {
            $isOrTag = preg_match('/^~(?<name>[^:]+)$/', $item, $matches);
            // Note that in the or tag case, we're storing just the tag name for simplicity.
            if ($isOrTag) $orTags[] = $matches['name']; else $other[] = $item;
        }

        // If there were or-tags defined, we should build a TagsOrRule with them.
        if (!empty($orTags)) $this->rules[] = new TagsOrRule($orTags);

        $invalidRules = []; // Whatever we can't parse will be stored here.

        // Build all the other rules.
        foreach ($other as $ruleText)
        {
            $parsed = Rules::tryParse($ruleText);
            if ($parsed) $this->rules[] = $parsed;
            else $invalidRules[] = $ruleText;
        }

        // Eventually we should also parse the invalid rules for other things
        // like ordering and such. For now, just deem them all incomprehensible.
        $this->incomprehensible = $invalidRules;
    }

    // Check a post against all rules defined in the query.
    function check(Post $post)
    {
        if (empty($this->rules)) return false;

        foreach ($this->rules as $rule)
        {
            if (!$rule->check($post)) return false;
        }

        return true;
    }
}
