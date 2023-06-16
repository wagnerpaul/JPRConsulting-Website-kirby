<?php
    
	$kirby = kirby();
    $visitor = $kirby->visitor();
	$ip = $visitor->ip();
	$url = $kirby->site()->page()->url();
    
?>
Hello Company,

<p><?= $text ?></p>

<p>Best regards,</p>
<p><?= $sender ?></p>

<p>Visitor IP: <?= $ip ?></p>
<p>Page URL: <?= $url ?></p>