<?php
    
	$kirby = kirby();
    $visitor = $kirby->visitor();
	$ip = $visitor->ip();
	$url = $kirby->site()->page()->url();
    
?>
Hello Company,

<?= $text ?>

Best regards,
<?= $sender ?>

Visitor IP: <?= $ip ?>
Page URL: <?= $url ?>