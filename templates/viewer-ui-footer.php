<?php namespace Christina; ?>
<footer>
    <?= Template::render('viewer-ui-picker', compact('id')); ?>
    <nav id="post-links">
        <a class="small-button" href="<?= Routes::url("post/$id.html"); ?>">HTML Full View</a>
        <a class="small-button" href="<?= Routes::url("post/$id.json"); ?>">JSON</a>
        <a class="small-button" href="<?= Routes::url("post/$id.min.json"); ?>">Minified JSON</a>
        <a class="small-button" href="<?= Routes::url("post/$id"); ?>">Image</a>
    </nav>
</footer>
