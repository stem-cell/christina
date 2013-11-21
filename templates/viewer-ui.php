<?php namespace Christina;
    $prev = $id - 1;
    $next = $id + 1;
?>
<div class="viewer">
    <a
        class="nav-button left<?= $prev ? '' : ' disabled'; ?>"
        href="<?= Routes::url("viewer/$prev"); ?>"
        title="Previous post">
        <span>◄</span>
    </a>
    <div class="iframe-wrap">
        <iframe src="<?= Routes::url("post/$id.html"); ?>" frameborder="0"></iframe>
    </div>
    <a
        class="nav-button right<?= $next > Posts::lastId() ? ' disabled' : ''; ?>"
        href="<?= Routes::url("viewer/$next"); ?>"
        title="Next post">
        <span>►</span>
    </a>
</div>
<footer>This is a footer! Really!</footer>
