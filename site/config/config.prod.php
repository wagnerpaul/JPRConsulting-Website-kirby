<?php

return [
    'env' => 'prod',
    'debug' => false,
    'cache' => [
        'pages' => [
            'active' => true,
        ]
    ],
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

