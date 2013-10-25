<?php namespace Christina;

// Function to include a script inside the Phar.
// Note that the name does not require the .php extension.
function using($name)
{
    require_once __DIR__."/$name.php";
}

// I don't blame you 'cause most of the time I don't, either :)
function youProbablyDontKnowWhatYouAreDoing() {
    echo nl2br("HypnoHub Hover Zoom ".CHRISTINA_VERSION." API endpoint"
              ." - please provide a valid query, my young grasshopper. \n\n");
    echo nl2br("This is what you gave us on <code>\$_GET</code>, see? No valid query data:\n");
    echo '<pre>';
    var_dump($_GET);
    echo '</pre>';
}
