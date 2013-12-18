<?php namespace Christina;

// Outputs an user's information in a way that can be inserted inline to the text.
// Note that this template assumes a valid user. Use Template::inlineUser() for more safety.

?>
<a class="inline-user" href="<?= $user->profileUrl(); ?>">
    <div class="pic"><img src="<?= $user->avatar->url; ?>"/></div>
    <span class="name"><?= $user->name; ?></span>
</a>
