<?php

return [
    'max_length' => env('COMMENTS_MAX_LENGTH', 1000),
    'allow_guest_replies' => env('COMMENTS_ALLOW_GUEST_REPLIES', true),
    'per_page' => env('COMMENTS_PER_PAGE', 15),
    'guest_name_required' => env('COMMENTS_GUEST_NAME_REQUIRED', true),
    'guest_name_max_length' => env('COMMENTS_GUEST_NAME_MAX', 100),
    'user_model' => env('COMMENTS_USER_MODEL', \App\Models\User::class),
];
