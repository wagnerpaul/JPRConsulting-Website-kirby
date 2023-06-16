<?php 

$blockID            = $data->blockID()->isNotEmpty() ? 'id="' . $data->blockID()->value() . '" ' : null;
$blockClass         = $data->blockClass()->isNotEmpty() ? ' ' . $data->blockClass()->value() : null;
$marginVertical     = $data->marginVertical()->isNotEmpty() ? $data->marginVertical() : null;
$marginLeft         = $data->marginLeft()->isNotEmpty() ? $data->marginLeft() : null;
$marginRight        = $data->marginRight()->isNotEmpty() ? $data->marginRight() : null;
$animation          = $data->animationSwitch()->isTrue() ? ' uk-scrollspy="cls:' . $data->animationType()->or('uk-animation-fade') . '; delay:' . $data->animationDelay()->or('200')->toInt() . '"' : null;
$cardSize           = $data->cardSize()->isNotEmpty() ? $data->cardSize() : null;
$cardHover          = $data->cardHover()->isTrue() ? ' uk-card-hover' : null;
$cardColor          = $data->cardColor()->isNotEmpty() && $data->cardColor() != "custom" ? $data->cardColor() : null;
$textColor          = $data->cardColor() == 'custom' && $data->textColor()->isNotEmpty() ? $data->textColor() : null;
$customBackground   = $data->cardColor() == "custom" ? ' uk-cover-container' : null;

?>
<div <?= $blockID ?>class="uk-card uk-card-body<?= $cardSize ?><?= $cardHover ?><?= $cardColor ?><?= $textColor ?><?= $customBackground ?><?= $blockClass ?><?= $marginVertical ?><?= $marginLeft ?><?= $marginRight ?>"<?= $animation ?>>
<?php if($data->cardBadge()->isNotEmpty()): ?><div class="uk-card-badge uk-label"><?= $data->cardBadge() ?></div><?php endif ?>
<?php if($data->cardColor() == 'custom'): ?>
<?php if($data->backgroundStyle() == 'image'): ?>
    <?php if($img = $data->backgroundImage()->toFile()): ?>
    <div class="uk-position-cover<?= $data->backgroundImagePosition() ?><?= $data->backgroundImageSize() ?><?php e($site->siteBorderRadius() == "true", ' uk-border-rounded') ?>" sources="srcset: <?= $img->thumb(['format' => 'webp'])->url() ?>" data-src="<?= $img->url() ?>" style="background-blend-mode: overlay; background-repeat:<?= $data->backgroundImageRepeat() ?>"<?php e($data->backgroundParalax() == "paralaxY", ' uk-parallax="bgy: -200"') ?><?php e($data->backgroundParalax() == "paralaxX", ' uk-parallax="bgx: -100"') ?> uk-img></div>
    <?php endif ?>
<?php elseif($data->backgroundStyle() == 'video'): ?>
    <?php if($data->mediaVideoSource() == "upload"): ?>
    <?php if($video = $data->mediaVideo()->toFile()): ?>
    <video src="<?= $video->url() ?>" autoplay loop muted playsinline uk-cover></video>
    <?php endif ?>
    <?php elseif($data->mediaVideoUrl()->isNotEmpty()): ?>
    <iframe src="<?= $data->mediaVideoUrl() ?>" width="1920" height="1080" frameborder="0" allowfullscreen uk-cover></iframe>
    <?php endif ?>
<?php endif ?>
<div class="uk-position-cover<?php e($site->siteBorderRadius() == "true", ' uk-border-rounded') ?>" style="<?php if($data->backgroundGradientOverlay()->isTrue()): ?>background-image: linear-gradient(<?php e($data->backgroundGradientTransition()->isNotEmpty(), $data->backgroundGradientTransition() . ', ') ?><?= $data->backgroundOverlayColor() ?>, <?= $data->backgroundOverlayColor2() ?>)<?php else: ?>background-color: <?= $data->backgroundOverlayColor() ?><?php endif ?>; background-blend-mode: overlay;  "></div>
<div class="uk-position-relative">
<?= $data->blocks()->toBlocks() ?>
</div>
<?php else: ?>
<?= $data->blocks()->toBlocks() ?>
<?php endif ?>
</div>