<?php

return [
    'cache' => [
        'pages' => [
            'active' => true,
        ]
    ],
 	//deployment webhook config
    'pju.webhook-field.endpoint' => env('WEBHOOKS_ENDPOINT'),
    'pju.webhook-field.hooks' => [
        env('DEPLOY_CONTENT_HOOK') => [
          'url' => '/'.env('DEPLOY_CONTENT_HOOK').'/start',
          'payload' => ['key' => env('DEPLOY_CONTENT_KEY')],
          'showOutdated' => false
        ]
    ],
];
