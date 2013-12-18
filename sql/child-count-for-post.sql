-- Calculates the number of child posts from a given post's ID.
SELECT
    count(*) AS count
FROM
    posts
WHERE
    parent_id = ?
