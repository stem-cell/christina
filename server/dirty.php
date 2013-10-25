<?php namespace Christina;

// This script will be cleaned up.

// Gets the URL for the resized image (or original if no resized image present)
// of a post, and assumes a valid array map of the respective database row.
// The returned URL will be an absolute URL (starting with slash), without host.
function postImageUrl($post) {
    if (!isset($post['md5'], $post['file_ext'])) throw new Exception();
    $md5 = $post['md5'];
    $ext = $post['file_ext'];
    $base = '/data';
    // Prefer a resized sample.
    if (@$post['sample_size']) {
        return "$base/sample/$md5.jpg";
    }
    // Otherwise just serve the original.
    return "$base/image/$md5.$ext";
}

// This script may now be called also as a require.
if (!$GLOBALS['disable-hhhz']) {
    // Let's do our thing.
    if (Query::isPost()) {
        
        $id = intval($_GET['post']);
        $post = Posts::get($id);
        $blacklist = BlackLists::get(@$_COOKIE['user_id']);
        $postArray = $post ? (array)$post : array();
        $tags = Posts::getTags($postArray);
        
        try {
            $display = postImageUrl((array)$post);
        } catch (Exception $e) {
            print_r((array)$post);
            exit;
            $display = CHRISTINA_404;
        }
        
        if (Query::isJson()) {
            $response = array(
                'version' => CHRISTINA_VERSION,
                'success' => !!$post,
                'display' => $display,
                'raw' => $postArray,
                'tags' => $tags
            );
            $response['blacklisted'] = Blacklists::check($tags, $blacklist);
            $json = json_encode($response, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
            echo $json;
        } else {
            if ($post) {
                if (Blacklists::check($tags, $blacklist)) {
                    Response::showBlacklistedImage();
                    exit();
                } else {
                    header("Location: $display");
                }
                Response::someThingsNeverChange();
                exit();
            } else {
                header('Location: /404');
                exit();
            }
        }
    } else Response::youProbablyDontKnowWhatYouAreDoing();
}
