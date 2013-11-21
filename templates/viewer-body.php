<?php namespace Christina; ?>
<h1><b>Christina</b> Post Viewer</h1>
<div class="viewer">
    <a class="nav-button left" href="<?= Routes::url('viewer/1'); ?>" title="Previous post">
        <span>◄</span>
    </a>
    <div class="iframe-wrap">
        <iframe src="<?= Routes::url('post/2.html'); ?>" frameborder="0"></iframe>
    </div>
    <a class="nav-button right" href="<?= Routes::url('viewer/3'); ?>" title="Next post">
        <span>►</span>
    </a>
</div>
<footer>This is a footer! Really!</footer>
