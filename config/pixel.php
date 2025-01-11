<?php

return [

    // ID do Pixel do Facebook
    'id' => env('FACEBOOK_PIXEL_ID', '1078228973780980'),

    // Funções para rastreamento de eventos do Pixel
    'events' => [
        'pageView' => function() {
            return "fbq('track', 'PageView');";
        },

        'viewContent' => function($contentId, $contentType = null) {
            $data = json_encode(['content_ids' => [$contentId]]);
            if ($contentType) {
                $data = json_encode(['content_ids' => [$contentId], 'content_type' => $contentType]);
            }
            return "fbq('track', 'ViewContent', {$data});";
        },

        'initiateCheckout' => function($value, $currency, $contentIds = []) {
            $data = json_encode(['value' => $value, 'currency' => $currency, 'content_ids' => $contentIds]);
            return "fbq('track', 'InitiateCheckout', {$data});";
        },

        'purchase' => function($value, $currency, $contentIds = []) {
            $data = json_encode(['value' => $value, 'currency' => $currency, 'content_ids' => $contentIds]);
            return "fbq('track', 'Purchase', {$data});";
        },

        'lead' => function() {
            return "fbq('track', 'Lead');";
        },

        'contact' => function($email = null) {
            $data = json_encode(['email' => $email]);
            return "fbq('track', 'Contact', {$data});";
        },
    ],
];
