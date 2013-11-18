<?php namespace Christina; ?>
<h1><b>Christina</b> Feature Support</h1>
<div class="wrap">
    <h2>Server-Side Features</h2>
    <ul>
    <?php foreach (Features::serverSide() as $name => $support): ?>
        <li><?= $name ?>: <b><?php if(!$support) echo 'not '; ?>supported</b></li>
    <?php endforeach; ?>
    </ul>
</div>
