<?php namespace Christina; ?>
<h1><b>Christina</b> Dashboard</h1>
<h2>(Not logged in)</h2>
<div class="wrap">
    <ul>
    <?php foreach (Dashboard::links() as $link): ?>
        <?= Template::render('dashboard-list-item', compact('link')); ?>
    <?php endforeach; ?>
    </ul>
</div>
