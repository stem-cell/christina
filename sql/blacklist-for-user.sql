SELECT
    tags
FROM
    user_blacklisted_tags
WHERE
    user_id = ?
