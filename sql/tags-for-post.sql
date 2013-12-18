-- Gets all tags for a given post.
--
-- You know, SQL is sometimes kinda ugly and hard to read, for
-- a language that's pretty much written in English.
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
