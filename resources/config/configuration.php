<?php

return [
    'multilingual_feeder' => [
        'type'   => 'anomaly.field_type.boolean',
        'config' => [
            'default_value' => true
        ],
    ],
    'url' => [
        'type'   => 'anomaly.field_type.url',
        'config' => [
            'default_value' => 'https://openclassify.com/{locale}/posts/rss.xml',
        ],
    ],
];
