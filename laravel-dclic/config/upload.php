<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour les uploads de fichiers
    |
    */

    'max_upload_size' => 7 * 1024, // 7MB en KB
    'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
];
