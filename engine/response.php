<?php namespace Christina;

class Response
{
    // Shows the image that tells the user this post was blacklisted by him.
    static function showBlacklistedImage()
    {
        header('Content-Type: image/png');
        echo Images::get('blacklisted');
        Response::someThingsNeverChange(); // like people's minds about blacklists.
    }

    // Tells the browser not to bother making a request next time as the content
    // will stay the same. A basic kind of optimization.
    static function someThingsNeverChange()
    {
        // Note that HTTP 1.1 states that the date shouldn't be more than one year
        // into the future. See section 14.21 ("Expires") of RFC 2616 (HTTP 1.1),
        // specifically the first paragraph of page 127.
        $secondsInYear = 60 * 60 * 24 * 365;
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $secondsInYear)); 
    }

    // I don't blame you 'cause most of the time I don't, either :)
    static function youProbablyDontKnowWhatYouAreDoing()
    {
        echo nl2br("Christina ".CHRISTINA_VERSION." API endpoint"
                  ." - please provide a valid query, my young grasshopper. \n\n");
        echo nl2br("This is what you gave us on <code>\$_GET</code>, see? No valid query data:\n");
        echo '<pre>';
        var_dump($_GET);
        echo '</pre>';
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
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'htm' => 'text/html',
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'text/javascript', // Obsolete, but see stackoverflow.com/a/4101763/124119
        ];

        if (isset($types[$type])) $type = $types[$type];

        $header = "content-type: $type";
        $baseType = explode('/', $type)[0];

        if ($baseType === 'text') $header .= '; charset=utf-8';

        header($header);
    }
}
