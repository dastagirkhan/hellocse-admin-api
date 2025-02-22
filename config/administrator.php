<?php

return [
    'login' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5), // Default to 5 attempts
        'decay_minutes' => env('LOGIN_DECAY_MINUTES', 1), // Default to 1 minute
    ],
];
