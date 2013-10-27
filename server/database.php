<?php namespace Christina;

use \Symfony\Component\Yaml\Yaml as SfYaml;

// Class that handles database stuff.
class DB
{
	// List of named queries, implemented as prepared statements.
	private static $queries = [
		'getPostById' => 'SELECT * FROM posts WHERE id = :id',
		'getTagsForPost' => 'SELECT name FROM tags INNER JOIN posts_tags ON tag_id = tags.id INNER JOIN posts ON post_id = posts.id WHERE post_id = :id',
		'getBlacklistForUser' => 'SELECT tags FROM user_blacklisted_tags WHERE user_id = :id'
	];

	// Get required credentials and connect to the local database.
	// The PDO object will be cached as a global variable for extra optimization.
	// Kinda sad that we can't serialize the PDO object for moar caching goodness.
	static function connect()
	{
	    $auth = cache('pdo_bear', function() {
	        return SfYaml::parse('../config/database.yml')['production'];
	    });

	    if (isset($GLOBALS['pdo_bear'])) return $GLOBALS['pdo_bear'];

	    $dsn = $auth['adapter'].':host='.$auth['host'].';dbname='.$auth['database'];
	    $pdo_bear = new \PDO($dsn, $auth['username'], $auth['password']);
	    return $GLOBALS['pdo_bear'] = $pdo_bear;
	}

	// Simplify making a (parameterized) query.
	static function query($sql, $params = [])
	{
		$pdo = self::connect();

		if (isset(self::$queries[$sql]))
		{
			$statement = $pdo->prepare(self::$queries[$sql]);
			$statement->execute($params);
			return $statement;
		}
		else
		{
			return $pdo->query($sql);
		}
	}
}
