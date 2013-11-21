<?php namespace Christina;

// Class that handles database stuff.
class DB
{
    // List of named queries, implemented as prepared statements.
    private static $queries = [
        'postById' => 'SELECT * FROM posts WHERE id = ?',
        'userById' => 'SELECT name, level, email FROM users WHERE id = ?',
        'tagsForPost' => 'SELECT name FROM tags INNER JOIN posts_tags ON tag_id = tags.id INNER JOIN posts ON post_id = posts.id WHERE post_id = ?',
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
        return $GLOBALS['pdo_bear'] = $pdo_bear;
    }

    // Simplify making a (parameterized) query.
    static function query($sql, $params = [])
    {
        $pdo = DB::connect();

        if (isset(DB::$queries[$sql]))
        {
            is_array($params) or $params = [$params];
            $statement = $pdo->prepare(DB::$queries[$sql]);
            $statement->execute($params);
            return $statement;
        }
        else
        {
            return $pdo->query($sql);
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
        return (array)$query->fetchObject();
    }
}
