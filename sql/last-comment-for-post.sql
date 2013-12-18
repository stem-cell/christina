-- Gets the last comment for a post by the post's ID.
SELECT
    id,
    post_id,
    user_id,
    created_at,
    updated_at,
    body,
    is_spam
FROM
    comments
WHERE
    post_id = ?
ORDER BY
    updated_at DESC
LIMIT
    1
