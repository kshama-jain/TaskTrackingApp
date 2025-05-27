<?php
if (isset($phone_no)) {

    echo "Notification sent to phone number: " . $phone_no;

    $accessToken = 'EAAbTzSfoZCw0BO2gbTuqwpd2jApb38sBFk98I1unNsiEetvzpAr7ma6qnqh4QNbm3WZCmL1po3oG6ziwQoi0tRMsfTMxiORtt2hIon0V8rDbyPWfgx60TL8iOwYfcVMtfGADMB7tLOEtZAQf140i3KxZABE7HnABZCJwQoGv5zjWAA8ZBFeRViBFQlGpYtYedq';
    $phoneNumberId = '374854242373692';
    $recipientPhoneNumber = $phone_no; 
    $message = "*TASK UPDATE alert:*\n\n" . $title . "\n" . "due " . $end;

    
    $url = "https://graph.facebook.com/v16.0/$phoneNumberId/messages";
    
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $recipientPhoneNumber,
        'type' => 'text',
        'text' => ['body' => $message],
    ];
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        echo 'Message sent successfully!';
    } else {
        echo 'Failed to send message.';
    }
} else {
    echo "Phone number not provided.";
}
    