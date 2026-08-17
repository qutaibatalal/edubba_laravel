<?php

return [
    'path' => env('BACKUP_PATH', storage_path('app/backups')),
    'mysqldump_path' => env('MYSQLDUMP_PATH', 'mysqldump'),
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),
];
