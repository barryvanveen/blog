<?php

declare(strict_types=1);

return [
    'comments' => [
        'total' => env('THROTTLING_COMMENTS_TOTAL', 10),
        'individual' => env('THROTTLING_COMMENTS_INDIVIDUAL', 3),
    ],
    'login' => [
        'total' => env('THROTTLING_LOGIN_TOTAL', 10),
        'individual' => env('THROTTLING_LOGIN_INDIVIDUAL', 3),
    ],
];
