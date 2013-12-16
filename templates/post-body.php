<?php namespace Christina; ?>
<div class="info">
    <h2>Post #<?= $post->id; ?></h2>
    <aside class="metadata">
        <div class="content">
            <?= Template::render('post-metadata', compact('post')); ?>
        </div>
    </aside>
    <aside class="tags">
        <ul>
            <?php foreach ($post->tags() as $tag)
                echo Template::render('post-tag', compact('tag'));
            ?>
        </ul>
    </aside>
</div>
<span class="hover">Hover for Info</span>
<img src="<?= $post->display; ?>">
