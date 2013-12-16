<?php namespace Christina;
    $class = 'tag-'.html($tag->typeName());
    $url = $tag->url();
    $name = $tag->name;
?><li class="<?= $class; ?>"><a target="_parent" href="<?= $url; ?>"><?= $name; ?></a>
    <?= $tag->postCount; ?>
</li>