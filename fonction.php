<?php
function sendRequestToWitAI($message) {
    $witAccessToken = 'YOUR_WIT_AI_ACCESS_TOKEN';
    $url = 'https://api.wit.ai/message?q=' . urlencode($message);
    $headers = [
        'Authorization: Bearer ' . $witAccessToken,
        'Content-Type: application/json'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Exemple d'utilisation de la fonction
$message = 'wawtelecom cest quoi';
$response = sendRequestToWitAI($message);
print_r($response);
