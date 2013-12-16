<?php namespace Christina;

// Abstracts a tag model.
class Tag
{
    // Tag name.
    public $name;

    // How many posts there are under this tag.
    public $postCount;

    // Tag type. Definitions can be retrieved by Tag::types().
    public $type;

    // Seems like being an ambiguous tag means users should be redirected
    // to a wiki for a disambiguation page. Like a "sakura" tag.
    public $isAmbiguous;

    // A cached version of ->typeName().
    private $typeName;

    // Build a tag model from raw data describing it.
    function __construct($name, $postCount, $tagType, $isAmbiguous)
    {
        $this->name        = $name;
        $this->postCount   = $postCount;
        $this->type        = $tagType;
        $this->isAmbiguous = $isAmbiguous;
        $this->typeName    = $this->typeName();
    }

    // Get the tag types configured by MyImoutoBooru.
    // The default should be (with aliases omitted):
    // [ "general"   => 0,
    //   "artist"    => 1,
    //   "copyright" => 3,
    //   "character" => 4,
    //   "circle"    => 5,
    //   "fault"     => 6 ]
    static function types()
    {
        return Environment::config()->tag_types;
    }

    // Convenience functions to check if the tag is of a particular type.
    function isGeneral()   { return $this->type === Tag::types()['general'];   }
    function isArtist()    { return $this->type === Tag::types()['artist'];    }
    function isCopyright() { return $this->type === Tag::types()['copyright']; }
    function isCharacter() { return $this->type === Tag::types()['character']; }
    function isCircle()    { return $this->type === Tag::types()['circle'];    }
    function isFault()     { return $this->type === Tag::types()['fault'];     }

    // Get the type name as a string.
    function typeName()
    {
        $types = Tag::types();
        $names = ['general', 'artist', 'copyright', 'character', 'circle', 'fault'];
        foreach ($names as $name) if ($this->type === $types[$name]) return $name;
        // If we didn't return anything yet, we'll have to iterate over the types
        // and try to find anything that matches.
        foreach ($types as $name => $type) if ($this->type === $type) return $name;
        // If we get here, then well, fuck you.
        return 'undefined';
    }

    // Returns the URL that should be loaded when clicking on a tag.
    function url()
    {
        $name = urlencode($this->name);
        return "/post?tags=$name";
    }

    // Returns the tag data into a format that can be sent over the wire.
    function representation()
    {
        return [
            'name'      => $this->name,
            'type'      => $this->typeName(),
            'postCount' => $this->postCount
        ];
    }
}
