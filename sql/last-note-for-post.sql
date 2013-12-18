SELECT
    id,
    post_id,
    user_id,
    created_at,
    updated_at,
    body,
    version,
    is_active,
    x,
    y,
    width,
    height
FROM
    notes
WHERE
    post_id = ?
ORDER BY
    updated_at DESC
LIMIT
    1
