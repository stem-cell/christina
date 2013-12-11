<?php namespace Christina;

// Abstract post access.
class Posts
{
    // Get information about a post in a Post instance, by numerical ID.
    static function get($id)
    {
        $data = DB::object('postById', $id);
        if ($data) $data['id'] = $id;
        return $data ? new Post($data) : null;
    }

    // Gets a list of tag objects for a post by id.
    static function getTags($id)
    {
        $rows = DB::rows('tagsForPost', $id);
        $list = array_map(function($i) { return new Tag($i[0], $i[1], $i[2], $i[3]); }, $rows);
        return $list;
    }

    // Gets the URL for the resized image of a post (or original if no resized present).
    // The returned URL will be an absolute URL (starting with slash), without host.
    // If the post data is invalid, it will return a 404 image.
    // If $type is set, an URL for a specific representation of the image will be returned.
    // Note that it will not check if the representation asked for actually exists.
    static function imageUrl($post, $type = null)
    {
        if (!$post) return CHRISTINA_404;

        $base = '/data';
        $md5 = $post->md5;
        $ext = $post->ext;

        // Should we directly return a specific type, or try to make a sensible decision?
        // By the way, there are few times I think a switch is even readable,
        // but this one is sexy!
        if ($type) switch ($type)
        {
            case 'original':  return "$base/image/$md5.$ext";
            case 'sample':    return "$base/sample/$md5.jpg";
            case 'jpeg':      return "$base/jpeg/$md5.jpg";
            case 'thumbnail': return "$base/preview/$md5.jpg";
        }

        // Prefer a resized sample.
        if ($post->sample) return Posts::imageUrl($post, 'sample');

        // Otherwise just serve the original.
        return Posts::imageUrl($post, 'original');
    }

    // Shows a post image to the user.
    static function showImage($id)
    {
        $post = Posts::get($id);

        if ($post)
        {
            if (Blacklists::check($post))
            {
                Response::showBlacklistedImage();
            }
            else
            {
                Response::redirect(Posts::imageUrl($post));
                Response::someThingsNeverChange();
            }
        }
        else
        {
            Response::redirect('/404');
        }
    }

    // Shows a post's information as JSON to the user.
    static function showJson($id, $min = true)
    {
        $post = Posts::get($id);

        $response = [
            'version' => CHRISTINA_VERSION,
            'success' => !!$post
        ];

        if ($post) $response += $post->representation();

        $flags = JSON_NUMERIC_CHECK;
        if (!$min) $flags |= JSON_PRETTY_PRINT;
        $json = json_encode($response, $flags);
        echo $json;
    }

    // Shows a post's information in an HTML page.
    static function showHtml($id)
    {
        $post = Posts::get($id);
        Template::display('post', compact('post'));
    }

    // Gets the last post ID (the latest post, most likely).
    static function lastId()
    {
        return DB::object('lastPostId')['id'];
    }

    // Expands a rating character into a word.
    static function expandRating($char, $ignoreErrors = false)
    {
        $char = $char[0];
        $expansions = ['s' => 'safe', 'q' => 'questionable', 'e' => 'explicit'];
        if (isset($expansions[$char])) return $expansions[$char];
        if ($ignoreErrors) return $expansions['q']; // Sensible default.
        throw new UnreachableException();
    }
}
