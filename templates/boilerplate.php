<?php namespace Christina; ?><!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8">
    <title><?php html($title); ?></title>
    <?php if (isset($css)) foreach ($css as $name) echo CSS::link($name); ?>
</head>

<body>
<?= $contents ?>
</body>

</html>
