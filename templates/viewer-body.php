<?php namespace Christina; ?>
<h1><b>Christina</b> Post Viewer</h1>
<?= $id ? Template::render('viewer-ui', compact('id')) : 'Choose a post!'; ?>
