<?php namespace Christina; ?>
<div class="info">
    <h2>Post #<?= $post['id']; ?></h2>
    <aside class="tags">
        <div class="content">
        <?php foreach ($tags as $tag): ?>
            <p><?= $tag; ?></p>
        <?php endforeach; ?>
        </div>
    </aside>
    <aside class="metadata">
        <p>File size: <?= $post['file_size']; ?></p>
        <p>MD5: <?= $post['md5']; ?></p>
        <p>Last commented at: <?= $post['last_commented_at']; ?></p>
        <p>File extension: <?= $post['file_ext']; ?></p>
        <p>Last time a note was added: <?= $post['last_noted_at']; ?></p>
        <p>Source: <?= $post['source']; ?></p>
        <p>Width: <?= $post['width']; ?></p>
        <p>Height: <?= $post['height']; ?></p>
        <p>Created at: <?= $post['created_at']; ?></p>
        <p>Rating: <?= $post['rating']; ?></p>
        <p>Score: <?= $post['score']; ?></p>
        <p>Is shown in index: <?= $post['is_shown_in_index']; ?></p>
        <p>Is held: <?= $post['is_held']; ?></p>
        <p>Has children: <?= $post['has_children']; ?></p>
        <p>Status: <?= $post['status']; ?></p>
        <p>Indexed at: <?= $post['index_timestamp']; ?></p>
        <p>Approved by: <?= $post['approver_id']; ?></p>
    </aside>
</div>
<span class="hover">Hover for Info</span>
<img src="<?= Posts::imageUrl((array)$post); ?>">
