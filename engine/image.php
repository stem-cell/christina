<?php namespace Christina;

// Represents image metadata.
class Image
{
    // Image width.
    public $width;

    // Image height.
    public $height;

    // Size of the image in bytes.
    // The presence of this value is optional.
    public $size = null;

    // Image URL. It's optional too, and should be a root URL from this server.
    // For example: '/foo/bar.jpg'
    public $url = null;

    // Builds an instance from width, height and optionally a size and/or URL.
    function __construct($width, $height, $size = null, $url = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->size = $size;
        $this->url = $url;
    }

    // Returns a representation that can be sent over the wire.
    function representation()
    {
        $representation = ['width' => $this->width, 'height' => $this->height];
        if ($this->size) $representation['size'] = $this->size;
        if ($this->url) $representation['url'] = $this->url;
        return $representation;
    }
}