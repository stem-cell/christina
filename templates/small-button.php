<?php namespace Christina;

$classes = $icon ? 'small-button icon'              : 'small-button';
$attr    = $icon ? " data-icon=\"$icon\""           : '';
$div     = $icon ? "<div class=\"icon-img\"></div>" : '';
$url     = Routes::url($route);

?>
<a class="<?= $classes; ?>" href="<?= $url; ?>"<?= $attr; ?>><?= $div; ?><?= $label; ?></a>
