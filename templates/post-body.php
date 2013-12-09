<?php namespace Christina; ?>
<div class="info">
    <h2>Post #<?= $post->id; ?></h2>
    <aside class="tags">
        <div class="content">
        <?php foreach ($post->tags() as $tag): ?>
            <p><?= $tag->name; ?></p>
        <?php endforeach; ?>
        </div>
    </aside>
    <aside class="metadata">
        <p>File size: <?= $post->image->size; ?></p>
        <p>MD5: <?= $post->md5; ?></p>
        <p>Last commented at: <?= humanDate($post->commented); ?></p>
        <p>File extension: <?= $post->ext; ?></p>
        <p>Last time a note was added: <?= humanDate($post->noted); ?></p>
        <p>Source: <?= $post->source; ?></p>
        <p>Width: <?= $post->image->width; ?></p>
        <p>Height: <?= $post->image->height; ?></p>
        <p>Created at: <?= humanDate($post->created); ?></p>
        <p>Rating: <?= $post->rating; ?></p>
        <p>Score: <?= $post->score; ?></p>
        <p>Is shown in index: <?= $post->isShownInIndex; ?></p>
        <p>Is held: <?= $post->isHeld; ?></p>
        <p>Has children: <?= $post->hasChildren; ?></p>
        <p>Status: <?= $post->status; ?></p>
        <p>Indexed at: <?= humanDate($post->indexed); ?></p>
        <p>Approved by: <?= $post->approver; ?></p>
    </aside>
</div>
<span class="hover">Hover for Info</span>
<img src="<?= $post->display; ?>">
