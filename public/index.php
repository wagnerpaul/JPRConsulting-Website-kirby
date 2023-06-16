<?php
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
include '../vendor/autoload.php';

$kirby = new Kirby([
    'roots' => [
        'index'    => __DIR__,
        'base'     => $base    = dirname(__DIR__),
        'content'  => $base . '/content',
        'site'     => $site = $base . '/site',
        'storage'  => $storage = $base . '/storage',
        'accounts' => $storage . '/accounts',
        'cache'    => $storage . '/cache',
        'sessions' => $storage . '/sessions',
        // 'blueprints'    => $site . '/theme/blueprints',
        // 'collections'   => $site . '/theme/collections',
        // 'controllers'   => $site . '/theme/controllers',
        // 'models'        => $site . '/theme/models',
        // 'snippets'      => $site . '/theme/snippets',
        // 'templates'     => $site . '/theme/templates'
    ]
]);
// echo '<pre>';
// echo var_dump($kirby->site());
// echo '</pre>';
echo $kirby->render();