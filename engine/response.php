<?php namespace Christina;

class Response
{
    // Tells the browser not to bother making a request next time as the content
    // will stay the same. A basic kind of optimization.
    static function cache()
    {
        // Note that HTTP 1.1 states that the date shouldn't be more than one year
        // into the future. See section 14.21 ("Expires") of RFC 2616 (HTTP 1.1),
        // specifically the first paragraph of page 127.
        $secondsInYear = 60 * 60 * 24 * 365;
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $secondsInYear)); 
    }

    // Redirects to a different URL.
    static function redirect($url)
    {
        header("Location: $url");
    }

    // Outputs a MIME (content-type) header for the given format.
    // The format can be given as a simple extension and it'll try to figure it out.
    // If the parsed MIME type is a text type, an UTF-8 encoding will be assumed.
    static function mimetype($type)
    {
        $type = strtolower($type);

        $types = [
            'gif'  => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'png'  => 'image/png',
            'css'  => 'text/css',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'js'   => 'application/javascript',
            'json' => 'application/json'
        ];

        if (isset($types[$type])) $type = $types[$type];

        $header = "Content-Type: $type";
        $baseType = explode('/', $type)[0];

        if ($baseType === 'text') $header .= '; charset=utf-8';

        header($header);
    }
}
