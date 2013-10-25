<?php namespace Christina;

// Main logic. Let's do our thing.
if (Query::isPost()) {
    
    $id = intval($_GET['post']);
    $post = Posts::get($id);
    $blacklist = BlackLists::get(@$_COOKIE['user_id']);
    $postArray = $post ? (array)$post : array();
    $tags = Posts::getTags($postArray);
    
    try {
        $display = Posts::imageUrl((array)$post);
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
