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
}
