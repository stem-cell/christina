<?php namespace Christina; ?>
<footer>
    <?= Template::render('viewer-ui-picker', compact('id')); ?>
    <nav id="post-links">
        <?= Template::smallButton('HTML Full View', "post/$id.html"    , 'html'); ?>
        <?= Template::smallButton('JSON'          , "post/$id.json"    , 'json'); ?>
        <?= Template::smallButton('Minified JSON' , "post/$id.min.json", 'min-json'); ?>
        <?= Template::smallButton('Image'         , "post/$id"         , 'image'); ?>
    </nav>
</footer>
