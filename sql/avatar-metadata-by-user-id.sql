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