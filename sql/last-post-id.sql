-- Gets the ID of the last post in the posts table.
SELECT
    id
FROM
    posts
ORDER BY
    id
DESC LIMIT
    0, 1
