-- Gets the tag blacklist from a given user's ID.
SELECT
    tags
FROM
    user_blacklisted_tags
WHERE
    user_id = ?
