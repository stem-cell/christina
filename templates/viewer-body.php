<?php namespace Christina; ?>
<h1><b>Christina</b> Post Viewer</h1>
<?= $id ? Template::render('viewer-ui', compact('id'))
        : Template::render('viewer-ui-picker', ['id' => Posts::lastId()]); ?>
