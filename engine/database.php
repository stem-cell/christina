<?php namespace Christina;

// Class that handles database stuff.
class DB
{
    // Cache of named queries, implemented as prepared statements.
    // They are actually stored as files in the sql folder, named with dashes.
    // Use this class' getSql method to get one.
    private static $queries = [];

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

        if (DB::getSql($sql))
        {
            is_array($params) or $params = [$params];
            $statement = $pdo_bear->prepare(DB::getSql($sql));
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

    // Gets the contents of a SQL file by its camelCase name.
    // For example, to get my-query-name.sql's contents you'd use:
    // Database::getSql('myQueryName');
    static function getSql($camelCaseName)
    {
        if (isset(DB::$queries[$camelCaseName])) return DB::$queries[$camelCaseName];
        $name = camelToDashes($camelCaseName);
        $base = dirname(__DIR__);
        $path = "$base/sql/$name.sql";
        $code = @file_get_contents($path);
        return DB::$queries[$camelCaseName] = $code ? $code : null;
    }
}
