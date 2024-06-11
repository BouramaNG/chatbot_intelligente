<?php
require 'vendor/autoload.php';

use OpenAI\Client;

// Charger la configuration
$config = require 'options.php';

// Récupérer le message de l'utilisateur
$userMessage = $_POST['message'];

// Vérifier si le message utilisateur correspond à une réponse prédéfinie
$responses = json_decode($config['responses'], true);
if (isset($responses[$userMessage])) {
    $response = $responses[$userMessage];
} else {
    // Si aucune réponse prédéfinie, appeler l'API OpenAI
    $openai = $config['openai_client'];
    $result = $openai->completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => $userMessage,
        'max_tokens' => 150,
    ]);
    $response = $result['choices'][0]['text'];
}

// Retourner la réponse JSON
echo json_encode(['response' => $response]);
