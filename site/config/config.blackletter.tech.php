<?php

return [
    
    // https://getkirby.com/docs/reference/system/options/debug
    'debug' => false,
    
    // Add here new config options. Always add comma at the end.
    'cache' => [
        'pages' => [
            'active' => false,
        ]
    ],
    'url' => 'https://blackletter.tech',
    'hooks' => [
        //styling switcher on prod should always be off
        'page.render:before' => function ($page) {
          $kirby = $this;
          $site = $kirby->site();
          $kirby->impersonate('kirby');
          if ($site->stylingSwitcher()->isTrue()) {
            $site->update([
              'stylingSwitcher' => false,
            ]);
          }

        }
    ],
];

