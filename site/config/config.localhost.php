<?php

return [
    
    // https://getkirby.com/docs/reference/system/options/debug
    'debug' => true,

    // More info on this setings at https://getkirby.com/docs/cookbook/forms/using-mailhog-for-email-testing#what-is-mailhog
    'email' => [
        'transport' => [
          'type' => 'smtp',
          'host' => 'localhost',
          'port' => 1025,
          'security' => false
        ]
    ],
    
    // Add here new config options. Always add comma at the end.
    'cache' => [
        'pages' => [
            'active' => false,
        ]
    ],
];


// Kirby::plugin('my/plugin', [
//   'siteMethods' => [
//     /* returns true if logged-in user and their role is in $roles (can be string or array) */
//     'userIs' => function ($roles) {
//       // the cookie check can be removed once core issue #3976 is resolved
//       if (!empty(Cookie::get(option('session.cookieName')))) {
//         if (kirby()->user() && in_array(kirby()->user()->role()->id(), (array)$roles)) {
//           return true;
//         }
//       }
//       return false;
//     },
//   ],
// ]);