<?php
return [
    'welcome_message' => 'Bienvenue Je suis votre WawBoT ! Comment puis-je vous aider aujourd\'hui ?',
    'chatbot_options' => json_encode([
        'suggerer_offres' => 'Suggérer moi des Offres',
        'easy_wawfi' => 'Qu\'est ce que le Easy WAWFI',
        'tester_eligibilite' => 'Tester votre Éligibilité',
        'service_commercial' => 'Parler au service Commercial',
        'technicien' => 'Parler à un Technicien'
    ]),
    'responses' => json_encode([
        'suggerer_offres' => 'Êtes-vous un particulier ou une entreprise ?',
        'particulier' => 'Visitez notre page pour les offres : <a href="https://wawtelecom.com/nos-offres">Nos Offres cliquez Ici</a>. Avez-vous d\'autres questions ?',
        'entreprise' => 'Visitez notre page pour les offres : <a href="https://wawtelecom.com/nos-offres">Nos Offres cliquez Ici</a>. Avez-vous d\'autres questions ?',
        'easy_wawfi' => 'La Easy WAWFI est un réseau wifi public que WAW a mis à la disposition de la population sénégalaise. La Easy WAWFI a pour mission de démocratiser l’accès internet à tout un chacun à des prix moindres. Le Giga est juste à 100fcfa. Nos Hospots se trouvent à Medina, Guediaway, Medina, Beaux Maraicher, etc. Pour plus d\'info, allez sur la page <a href="https://wawtelecom.com">Easy Waw-Fi</a>.',
        'tester_eligibilite' => 'Pour tester votre éligibilité, veuillez activer votre localisation avant tout et cliquez sur ce lien : <a href="https://wawtelecom.com/eligibility">Tester Éligibilité</a>.',
        'service_commercial' => 'Veuillez patienter, nous allons vous mettre en rapport avec le service client : mndour@wawtelecom.com.',
        'technicien' => 'Veuillez patienter, nous allons vous mettre en rapport avec un technicien.'
    ])
];
