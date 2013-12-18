SELECT
    count(*) AS count
FROM
    posts
WHERE
    parent_id = ?
