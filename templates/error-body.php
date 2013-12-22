<?php namespace Christina; ?>
<h1><b><?= $error::code; ?></b> <?= $error::name; ?></h1>
<?php if ($error::image): ?>
<img src="<?= Images::url($error::image) ?>" width="256" height="256" alt="http <?= $error::code; ?> error code icon">
<?php endif; ?>
<h2><?= html($error->description); ?></h2>
