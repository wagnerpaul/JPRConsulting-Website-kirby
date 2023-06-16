<?php

$mediaIndex = site()->mediaIndex()->isTrue() ? 'site.index(true).images' : 'model.images';

return [
    'label' => [
        'en' => 'Image',
        'de' => 'Bild',
      ],
    'type' => 'files',
    'multiple' => 'false',
    'uploads' => 'image',
    'query' => $mediaIndex,
    'info' => '{{ file.dimensions }} {{ file.niceSize }}',
    'image' => [
        'ratio' => '2/1',
        'cover' => 'true',
      ],
];