-- Selects avatar metadata from a given user's ID.
SELECT
    avatar_width,
    avatar_height,
    avatar_top,
    avatar_bottom,
    avatar_left,
    avatar_right
FROM
    users
WHERE
    id = ?
