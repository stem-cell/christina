<?php namespace Christina; ?>
<h1>Feature Support</h1>
<ul>
    <li>APC: <b><?php if(!Features::apc()) echo 'not '; ?>supported</b></li>
    <li>Memcache: <b><?php if(!Features::memcache()) echo 'not '; ?>supported</b></li>
    <li>Memcached: <b><?php if(!Features::memcached()) echo 'not '; ?>supported</b></li>
    <li>Ranting about PHP: <b><?php if(!T_PAAMAYIM_NEKUDOTAYIM) echo 'not '; ?>supported</b></li>
</ul>
