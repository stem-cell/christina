<?php namespace Christina;

$features = [
    'APC' => Features::apc(),
    'Memcache' => Features::memcache(),
    'Memcached' => Features::memcached(),
    'Ranting about PHP' => defined('T_PAAMAYIM_NEKUDOTAYIM')
];

?>
<h1>Feature Support</h1>
<ul>
<?php foreach ($features as $name => $support): ?>
    <li><?= $name ?>: <b><?php if(!$support) echo 'not '; ?>supported</b></li>
<?php endforeach; ?>
</ul>
