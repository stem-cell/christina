<?php namespace Christina; ?>
<h1><b>Christina</b> Dashboard</h1>
<?= Template::render('dashboard-header'); ?>
<div class="wrap">
    <ul>
    <?php foreach (Dashboard::links() as $link): ?>
        <?= Template::render('dashboard-list-item', compact('link')); ?>
    <?php endforeach; ?>
    </ul>
</div>
<footer><?= Environment::signature(); ?></footer>
