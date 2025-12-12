<?php

return [
    'slug' => env('ADMIN_PANEL_SLUG', 'gc-admin-' . substr(hash('sha256', base_path()), 0, 8)),
    'locked_notice' => env('ADMIN_LOCKED_NOTICE', 'This page does not exist.'),
    'locked_notice_mode' => env('ADMIN_LOCKED_NOTICE_MODE', '404'),
];

