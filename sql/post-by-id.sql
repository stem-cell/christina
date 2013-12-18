-- Gets a post by ID.
SELECT
    -- This is pretty much everything except the following columns:
    -- `id`, `ip_addr`, `preview_width`, `preview_height` and `random`
    user_id,
    file_size,
    md5,
    last_commented_at,
    file_ext,
    last_noted_at,
    source,
    width,
    height,
    created_at,
    rating,
    actual_preview_width,
    actual_preview_height,
    score,
    is_shown_in_index,
    is_held,
    has_children,
    status,
    is_rating_locked,
    is_note_locked,
    parent_id,
    sample_width,
    sample_height,
    sample_size,
    index_timestamp,
    jpeg_width,
    jpeg_height,
    jpeg_size,
    approver_id
FROM
    posts
WHERE
    id = ?
