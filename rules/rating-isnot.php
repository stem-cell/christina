<?php namespace Christina;

// Negative version of RatingIsRule.
class RatingIsNotRule extends RatingIsRule
{
    static protected $mode = false;
    
    static protected $pattern = '-rating:(?<rating>s|safe|q|questionable|e|explicit)';
}
