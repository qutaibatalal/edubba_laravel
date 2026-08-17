<?php

return [
    // Toggle + endpoint for the external face-recognition microservice.
    'enabled' => env('FACE_RECOGNITION_ENABLED', false),
    'service_url' => env('FACE_RECOGNITION_SERVICE_URL'),
    'api_key' => env('FACE_RECOGNITION_API_KEY'),
];
