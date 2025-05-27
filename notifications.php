<?php

// Replace these with your actual values
$accessToken = 'EAAbTzSfoZCw0BO2gbTuqwpd2jApb38sBFk98I1unNsiEetvzpAr7ma6qnqh4QNbm3WZCmL1po3oG6ziwQoi0tRMsfTMxiORtt2hIon0V8rDbyPWfgx60TL8iOwYfcVMtfGADMB7tLOEtZAQf140i3KxZABE7HnABZCJwQoGv5zjWAA8ZBFeRViBFQlGpYtYedq';
$phoneNumberId = '374854242373692';
$recipientPhoneNumber = '918105460074'; // Recipient's phone number in international format
$message = 'Hello, this is a test message from PHP!';

// Endpoint for sending messages
$url = "https://graph.facebook.com/v16.0/$phoneNumberId/messages";

// Set up the message data
$data = [
    'messaging_product' => 'whatsapp',
    'to' => $recipientPhoneNumber,
    'type' => 'text',
    'text' => ['body' => $message],
];

// Set up the request headers
$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
];

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the request
$response = curl_exec($ch);
curl_close($ch);

// Handle the response
if ($response) {
    echo 'Message sent successfully!';
    var_dump($response); // You can inspect the response if needed
} else {
    echo 'Failed to send message.';
}

?>
