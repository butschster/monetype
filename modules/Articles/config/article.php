<?php

return [
    'check'                    => [
        'test'                   => env('PLAGIARISM_TEST', true),
        'cost'                   => env('PLAGIARISM_COST', 1),
        'max_percent_plagiarism' => env('PLAGIARISM_MAX_PERCENT', 20)
    ],
    'min_tags'                 => 3,
    'words_per_minute_reading' => 150
];