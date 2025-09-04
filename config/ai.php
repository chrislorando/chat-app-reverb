<?php

return [
    'provider' => env('AI_PROVIDER', 'gemini'),

    'gemini' => [
        'api_key'  => env('GEMINI_API_KEY'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
        // 'model'    => env('GEMINI_IMAGE_MODEL', 'gemini-2.0-flash-preview-image-generation'),
        'model'    => env('GEMINI_IMAGE_MODEL', 'gemini-2.0-flash'),
        'content_api'    => env('GEMINI_CONTENT_API', 'streamGenerateContent'),
    ],

    'openai' => [
        'api_key'  => env('OPENAI_API_KEY'),
        'endpoint' => env('OPENAI_ENDPOINT', 'https://api.openai.com/v1'),
        'model'    => env('OPENAI_IMAGE_MODEL', 'gpt-image-1'),
    ],

 
];
