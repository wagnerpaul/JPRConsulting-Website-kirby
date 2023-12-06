<?php

Kirby::plugin('wagnerpaul/image-url', [
    'tags' => [
        'image-url' => [
        
        'html' => function ($tag) {
          if ($tag->file = $tag->file($tag->value)) {
            $tag->src     = $tag->file->url();
          } else {
            $tag->src = Url::to($tag->value);
          }
          return $tag->src;
        }
      ],
      'wikipedia' => [
        'attr' => [
          'class'
        ],
        'html' => function($tag) {
          return '<a class="' . $tag->class . '" href="http://wikipedia.org">Wikipedia</a>';
        }
      ]
    ]
]);