<?php namespace Christina; ?>
<form action="<?= Routes::url('viewer'); ?>" method="post" name="post" id="picker-form">
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
