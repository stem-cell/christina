<?php namespace Christina; ?>
<h1><b>Christina</b> Feature Support</h1>
<div class="wrap">
    <h2>Server-Side Features</h2>
    <ul>
    <?php foreach (Features::serverSide() as $name => $support): ?>
        <?= Template::render('features-list-item', compact('name', 'support')); ?>
    <?php endforeach; ?>
    </ul>
</div>
