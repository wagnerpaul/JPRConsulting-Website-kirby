<?php

return [
    
    // https://getkirby.com/docs/reference/system/options/debug
    'debug' => true,
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