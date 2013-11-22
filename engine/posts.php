<?php namespace Christina;

// Abstract post access.
class Posts
{
    // Get information about a post, by numerical ID.
    static function get($id)
    {
        return DB::object('postById', $id);
    }

    // Gets a (textual) list of tags for a post as an array. No tags are prefixed, except for rating:N.
    static function getTags($post)
    {
        if (!is_array($post) or !isset($post['id'])) return array();
        $rows = DB::rows('tagsForPost', $post['id']);
        $list = array_map(function($i) { return $i[0]; }, $rows);
        $list[] = 'rating:'.$post['rating'];
        return $list;
    }

    // Gets the URL for the resized image (or original if no resized image present)
    // of a post, and assumes a valid array map of the respective database row.
    // The returned URL will be an absolute URL (starting with slash), without host.
    // If the post data is invalid, it will return a 404 image.
    static function imageUrl($post)
    {
        if (!isset($post['md5'], $post['file_ext'])) return CHRISTINA_404;

        $md5 = $post['md5'];
        $ext = $post['file_ext'];
        $base = '/data';

        // Prefer a resized sample.
        if (@$post['sample_size'])
        {
            return "$base/sample/$md5.jpg";
        }

        // Otherwise just serve the original.
        return "$base/image/$md5.$ext";
    }

    // Shows a post image to the user.
    static function showImage($id)
    {
        $post = Posts::get($id);
        $tags = Posts::getTags((array)$post);
        
        if ($post)
        {
            if (Blacklists::check($tags))
            {
                Response::showBlacklistedImage();
            }
            else
            {
                header('Location: '.Posts::imageUrl((array)$post));
                Response::someThingsNeverChange();
            }
        }
        else
        {
            header('Location: /404');
        }
    }

    // Shows a post's information as JSON to the user.
    static function showJson($id, $min = true)
    {
        $post = Posts::get($id);
        $tags = Posts::getTags((array)$post);
        
        $response = [
            'version' => CHRISTINA_VERSION,
            'success' => !!$post,
            'display' => Posts::imageUrl((array)$post),
            'raw' => (array)$post,
            'tags' => $tags
        ];

        $response['blacklisted'] = Blacklists::check($tags);
        $flags = JSON_NUMERIC_CHECK;
        if (!$min) $flags |= JSON_PRETTY_PRINT;
        $json = json_encode($response, $flags);
        echo $json;
    }

    // Gets the last post ID (the latest post, most likely).
    static function lastId()
    {
        return DB::object('lastPostId')['id'];
    }
}
