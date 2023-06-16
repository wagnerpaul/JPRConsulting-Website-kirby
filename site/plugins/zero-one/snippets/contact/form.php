<a class="tm-contact" id="contact"></a>
<?php if($success): ?>
<div class="uk-alert-success" uk-alert>
    <p><?= $success ?></p>
</div>
<?php else: ?>
<?php if (isset($alert['error'])): ?>
<div class="uk-alert-danger" uk-alert>
    <p><?= $alert['error'] ?></p>
</div>
<?php endif ?>
<form class="uk-grid-small" method="post" action="<?= $page->url() ?><?php e($page->isHomePage(), '/') ?>#contact" uk-grid>
    <div class="tm-hon">
        <input type="checkbox" name="contact_me_by_fax_only" value="1" tabindex="-1" autocomplete="off">
    </div>
    <div class="uk-width-1-2@s">
        <label for="name">
        <?= $site->labelName()->html() ?> <abbr title="required">*</abbr>
        </label>
        <input class="uk-input" type="text" id="name" name="name" value="<?= esc($data['name'] ?? '') ?>" required>
        <?= isset($alert['name']) ? '<span class="uk-text-danger">' . html($alert['name']) . '</span>' : '' ?>
    </div>
    <div class="uk-width-1-2@s">
        <label for="email">
        <?= $site->labelEmail()->html() ?> <abbr title="required">*</abbr>
        </label>
        <input class="uk-input" type="email" id="email" name="email" value="<?= esc($data['email'] ?? '') ?>" required>
        <?= isset($alert['email']) ? '<span class="uk-text-danger">' . html($alert['email']) . '</span>' : '' ?>
    </div>
    <div class="uk-width-1-1">
        <label for="text">
        <?= $site->labelMessage()->html() ?> <abbr title="required">*</abbr>
        </label>
        <textarea class="uk-textarea uk-height-small" id="text" name="text" required><?= esc($data['text']?? '') ?></textarea>
        <?= isset($alert['text']) ? '<span class="uk-text-danger">' . html($alert['text']) . '</span>' : '' ?>
    </div>
    <div class="uk-width-1-1">
        <label><input class="uk-checkbox" id="privacy" name="privacy" type="checkbox" checked required> <?= $site->labelFormPrivacy()->or('I agree to the privacy policy') ?></label>
    </div>

    <div class="uk-width-1-2@s">
    <button class="uk-button uk-button-primary uk-margin-top" type="submit" name="submit" value="Submit"><?= $site->labelSubmit()->html() ?></button>
    </div>
</form>
<?php endif ?>