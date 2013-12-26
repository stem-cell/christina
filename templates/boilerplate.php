<?php namespace Christina; ?><!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8">
    <title><?= html($title); ?></title>
    <link rel="shortcut icon" href="<?= Images::url('favicon.ico'); ?>">
    <?php if (isset($css)) foreach ($css as $name) CSS::link($name); ?>
</head>

<body class="christina">
<?= $contents ?>
</body>

</html>
