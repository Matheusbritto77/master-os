<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization_id' => env('OPENAI_ORG_ID'),
    'api_url' => env('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions'),
];
