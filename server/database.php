<?php namespace Christina;

class DB
{
	// Get required credentials and connect to the local database.
	// The PDO object will be cached as a global variable for extra optimization.
	// Kinda sad that we can't serialize the PDO object for moar caching goodness.
	function connect()
	{
	    $auth = cache('pdo_bear', function() {
	        return SfYaml::parse('../config/database.yml')['production'];
	    });

	    if (isset($GLOBALS['pdo_bear'])) return $GLOBALS['pdo_bear'];

	    $dsn = $auth['adapter'].':host='.$auth['host'].';dbname='.$auth['database'];
	    $pdo_bear = new PDO($dsn, $auth['username'], $auth['password']);
	    return $GLOBALS['pdo_bear'] = $pdo_bear;
	}
}

