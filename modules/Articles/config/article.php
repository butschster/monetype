<?php

return [
    'check' => [
        'test'                   => env('PLAGIARISM_TEST', true),
        'cost'                   => env('PLAGIARISM_COST', 1),
        'max_percent_plagiarism' => env('PLAGIARISM_MAX_PERCENT', 20)
    ]
];