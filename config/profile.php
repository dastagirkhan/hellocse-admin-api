<?php

return [
    'pagination' => [
        'default_per_page' => env('PROFILE_PAGINATION_DEFAULT_PER_PAGE', 10), // Default to 10 items per page
        'max_per_page' => env('PROFILE_PAGINATION_MAX_PER_PAGE', 100), // Maximum allowed items per page
    ],
];
