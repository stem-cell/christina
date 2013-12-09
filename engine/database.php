<?php namespace Christina;

// Class that handles database stuff.
class DB
{
    // List of named queries, implemented as prepared statements.
    private static $queries = [
        'postById' => 'SELECT user_id, file_size, md5, last_commented_at, file_ext, last_noted_at, source, width, height, created_at, rating, actual_preview_width, actual_preview_height, score, is_shown_in_index, is_held, has_children, status, is_rating_locked, is_note_locked, parent_id, sample_width, sample_height, sample_size, index_timestamp, jpeg_width, jpeg_height, jpeg_size, approver_id FROM posts WHERE id = ?',
        'userById' => 'SELECT name, password_hash, level, email, avatar_post_id, avatar_timestamp FROM users WHERE id = ?',
        'userMetadataById' => 'SELECT created_at, my_tags, invite_count, invited_by, show_samples, show_advanced_editing, pool_browse_mode, use_browser, always_resize_images, last_logged_in_at, last_forum_topic_read_at, last_comment_read_at, last_deleted_post_seen_at, receive_dmails, has_mail FROM users WHERE id = ?',
        'avatarMetadataByUserId' => 'SELECT avatar_width, avatar_height, avatar_top, avatar_bottom, avatar_left, avatar_right FROM users WHERE id = ?',
        'tagsForPost' => 'SELECT name, post_count, tag_type, is_ambiguous FROM tags INNER JOIN posts_tags ON tag_id = tags.id INNER JOIN posts ON post_id = posts.id WHERE post_id = ?',
        'blacklistForUser' => 'SELECT tags FROM user_blacklisted_tags WHERE user_id = ?',
        'lastPostId' => 'SELECT id FROM posts ORDER BY id DESC LIMIT 0, 1'
    ];

    // Get required credentials and connect to the local database.
    // The PDO object will be cached as a global variable for extra optimization.
    // Kinda sad that we can't serialize the PDO object for moar caching goodness.
    static function connect()
    {
        $auth = cache('pdo_bear', function() {
            $configPath = Environment::resolve('../config/database.yml');
            return Yaml::parse($configPath)['production'];
        });

        if (isset($GLOBALS['pdo_bear'])) return $GLOBALS['pdo_bear'];

        $dsn = $auth['adapter'].':host='.$auth['host'].';dbname='.$auth['database'];
        $pdo_bear = new \PDO($dsn, $auth['username'], $auth['password']);
        DB::configure($pdo_bear);
        return $GLOBALS['pdo_bear'] = $pdo_bear;
    }

    // Configure a PDO object with desired settings.
    static function configure(&$pdo_bear)
    {
        $pdo_bear->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $pdo_bear->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
    }

    // Simplify making a (parameterized) query.
    static function query($sql, $params = [])
    {
        $pdo_bear = DB::connect();

        if (isset(DB::$queries[$sql]))
        {
            is_array($params) or $params = [$params];
            $statement = $pdo_bear->prepare(DB::$queries[$sql]);
            $statement->execute($params);
            return $statement;
        }
        else
        {
            return $pdo_bear->query($sql);
        }
    }

    // Returns an index of results (that is, an indexed database row).
    // This function is to be called like DB::query, but returns the
    // results directly (instead of a query object).
    static function rows($sql, $params = [])
    {
        $query = DB::query($sql, $params);
        return $query->fetchAll(\PDO::FETCH_NUM);
    }

    // Returns an object map for a one-row resulting query.
    // This function is to be called like DB::query, but returns the
    // result directly (instead of a query object).
    static function object($sql, $params = [])
    {
        $query = DB::query($sql, $params);
        $object = $query->fetchObject();
        return $object ? (array)$object : null;
    }
}
