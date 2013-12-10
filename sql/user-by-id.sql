SELECT
    name,
    password_hash,
    level,
    email,
    avatar_post_id,
    avatar_timestamp
FROM
    users
WHERE
    id = ?
