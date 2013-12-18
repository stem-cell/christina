SELECT
    post_id,
    user_id,
    created_at,
    updated_at,
    body,
    is_spam
FROM
    comments
WHERE
    id = ?
