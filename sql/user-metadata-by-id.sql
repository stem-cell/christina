-- Gets an user's extended data by ID.
SELECT
    created_at,
    my_tags,
    invite_count,
    invited_by,
    show_samples,
    show_advanced_editing,
    pool_browse_mode,
    use_browser,
    always_resize_images,
    last_logged_in_at,
    last_forum_topic_read_at,
    last_comment_read_at,
    last_deleted_post_seen_at,
    receive_dmails,
    has_mail
FROM
    users
WHERE
    id = ?
