<?php

// Configuration
$phoneNumberId = '374854242373692'; // Ensure this is correct
$accessToken = 'EAAbTzSfoZCw0BO2gbTuqwpd2jApb38sBFk98I1unNsiEetvzpAr7ma6qnqh4QNbm3WZCmL1po3oG6ziwQoi0tRMsfTMxiORtt2hIon0V8rDbyPWfgx60TL8iOwYfcVMtfGADMB7tLOEtZAQf140i3KxZABE7HnABZCJwQoGv5zjWAA8ZBFeRViBFQlGpYtYedq';
$toPhoneNumber = '8105460074';
$templateName = 'hello_world';
$languageCode = 'en_US';

// Data payload
$data = [
    'messaging_product' => 'whatsapp',
    'to' => $toPhoneNumber,
    'type' => 'template',
    'template' => [
        'name' => $templateName,
        'language' => ['code' => $languageCode]
    ]
];

// Headers and context for file_get_contents
$headers = [
    "Authorization: Bearer $accessToken",
    'Content-Type: application/json'
];
$options = [
    'http' => [
        'header' => implode("\r\n", $headers),
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];
$context = stream_context_create($options);

// API request
$response = @file_get_contents("https://graph.facebook.com/v20.0/$phoneNumberId/messages", false, $context);

if ($response === FALSE) {
    echo 'Failed to send notification.';
    // Additional error handling or logging can go here
} else {
    echo 'Notification sent successfully.';
    // Process response as needed
}

