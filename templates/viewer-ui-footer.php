<?php namespace Christina; ?>
<footer>
    <form action="<?= Routes::url('viewer'); ?>" method="post" name="post" id="footer-form">
        <label>
            View post
            <input
                type="number"
                name="number"
                required
                title="A value between 1 and <?= Posts::lastId(); ?>"
                style="width: <?= digits(Posts::lastId()) * 9 + 22; ?>px"
                min="1"
                max="<?= Posts::lastId(); ?>"
                value="<?= $id; ?>">
        </label>
        <button type="submit" class="small-button" title="(or press Enter)">Go</button>
    </form>
    <nav id="post-links">
        <a class="small-button" href="<?= Routes::url("post/$id.html"); ?>">HTML Full View</a>
        <a class="small-button" href="<?= Routes::url("post/$id.json"); ?>">JSON</a>
        <a class="small-button" href="<?= Routes::url("post/$id.min.json"); ?>">Minified JSON</a>
        <a class="small-button" href="<?= Routes::url("post/$id"); ?>">Image</a>
    </nav>
</footer>
