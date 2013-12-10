SELECT
    name,
    post_count,
    tag_type,
    is_ambiguous
FROM
    tags
INNER JOIN
    posts_tags
ON
    tag_id = tags.id
INNER JOIN
    posts
ON
    post_id = posts.id
WHERE
    post_id = ?
