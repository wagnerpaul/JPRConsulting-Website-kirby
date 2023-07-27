<?php
$standardConfig = include 'config.prod.php';

//put special configs here, they will replace the standard config
$extendedConfig = [
    //example for caching
    // 'cache' => [
    //     'pages' => [
    //         'active' => false,
    //     ]
    // ],
];

return array_replace_recursive($standardConfig, $extendedConfig);
