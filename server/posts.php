<?php namespace Christina;

// Abstract post access.
class Posts
{
	// Get information about a post, by numerical ID.
	function get($id) {
	    $pdo = DB::connect();
	    $query = $pdo->query("SELECT * FROM posts WHERE id = $id");
	    $row = $query->fetchObject();
	    return $row;
	}

	// Gets a (textual) list of tags for a post as an array. No tags are prefixed, except for rating:N.
	function getTags($post) {
	    if (!is_array($post) or !isset($post['id'])) return array();
	    $pdo = DB::connect();
	    $id = $post['id'];
	    $sql = "SELECT name FROM tags JOIN posts_tags ON tag_id = tags.id JOIN posts ON post_id = posts.id WHERE post_id = $id";
	    $query = $pdo->query($sql);
	    $rows = $query->fetchAll(PDO::FETCH_NUM);
	    $list = array_map(function($i) { return $i[0]; }, $rows);
	    $list[] = 'rating:'.$post['rating'];
	    return $list;
	}
}