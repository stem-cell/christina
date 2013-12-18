SELECT
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
    id = ?
