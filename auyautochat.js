jQuery(document).ready(function($) {
    var welcomeMessage = auyAutoChatOptions.welcome_message || 'Bienvenue ! Comment puis-je vous aider aujourd\'hui ?';
    var options = JSON.parse(auyAutoChatOptions.chatbot_options || '{}');
    var responses = JSON.parse(auyAutoChatOptions.responses || '{}');

    function sendMessage(message, isUser = false) {
        var messageClass = isUser ? 'user-message' : 'chatbot-message';
        $('#chatbot-messages').append('<div class="' + messageClass + '"><div class="message">' + message + '</div></div>');
        $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
    }

    function sendOptions(optionSet) {
        var optionsHtml = '<div class="chatbot-options">';
        $.each(optionSet, function(key, value) {
            optionsHtml += '<button class="chatbot-option" data-value="' + key + '">' + value + '</button>';
        });
        optionsHtml += '</div>';
        sendMessage(optionsHtml);
    }

    function resetChatbot() {
        $('#chatbot-messages').html('');
        sendMessage(welcomeMessage);
        sendOptions(options);
    }

    sendMessage(welcomeMessage);
    sendOptions(options);

    var currentStep = 'initial';

    function handleUserInput(userMessage) {
        var response;

        switch (currentStep) {
            case 'initial':
                if (userMessage in responses) {
                    response = responses[userMessage];
                    if (userMessage === 'suggerer_offres') {
                        currentStep = 'offers';
                        sendMessage(response);
                        sendOptions({
                            'particulier': 'Particulier',
                            'entreprise': 'Entreprise'
                        });
                    } else {
                        sendMessage(response);
                        currentStep = 'initial';
                        sendOptions(options);
                    }
                } else {
                    sendToVoiceflow(userMessage);
                }
                break;

            case 'offers':
                if (userMessage in responses) {
                    response = responses[userMessage];
                    sendMessage(response);
                    currentStep = 'initial';
                    sendOptions(options);
                } else {
                    sendMessage('Je n\'ai pas compris votre choix. Êtes-vous un particulier ou une entreprise ?');
                    sendOptions({
                        'particulier': 'Particulier',
                        'entreprise': 'Entreprise'
                    });
                }
                break;

            default:
                sendMessage('Je n\'ai pas compris votre choix. Veuillez choisir une option parmi :');
                sendOptions(options);
                currentStep = 'initial';
                break;
        }
    }

    function sendToVoiceflow(message) {
        $.ajax({
            url: auyAutoChatOptions.ajax_url,
            method: 'POST',
            data: {
                action: 'auyautochat_send_message',
                message: message
            },
            success: function(response) {
                if (response.success) {
                    sendMessage(response.data);
                } else {
                    sendMessage('Désolé, une erreur s\'est produite.');
                }
                sendOptions(options);
            },
            error: function(xhr, status, error) {
                sendMessage('Erreur lors de la communication avec le serveur.');
                sendOptions(options);
            }
        });
    }

    function sendTextMessage() {
        var userMessage = $('#chatbot-input').val();
        if (userMessage.trim() !== '') {
            sendMessage(userMessage, true);
            handleUserInput(userMessage);
            $('#chatbot-input').val('');
        }
    }

    $('#chatbot-input').on('keypress', function(e) {
        if (e.which == 13) {
            sendTextMessage();
        }
    });

    $('#send-text-message').on('click', function() {
        sendTextMessage();
    });

    $('#send-voice-message').on('click', function() {
        alert('Fonctionnalité de message vocal non encore implémentée.');
    });

    $('#chatbot-messages').on('click', '.chatbot-option', function() {
        var userMessage = $(this).data('value');
        var userText = $(this).text();
        sendMessage(userText, true);
        handleUserInput(userMessage);
    });

    if ($('#chatbot-logo').length) {
        $('#chatbot-logo').on('click', function() {
            $('#chatbot-container').toggleClass('hidden');
        });
    }

    if ($('#chatbot-close').length) {
        $('#chatbot-close').on('click', function() {
            $('#chatbot-container').addClass('hidden');
        });
    }

    if ($('#reset-chatbot').length) {
        $('#reset-chatbot').on('click', function() {
            resetChatbot();
        });
    }
});







// jQuery(document).ready(function($) {
//     var welcomeMessage = auyAutoChatOptions.welcome_message || 'Bienvenue ! Comment puis-je vous aider aujourd\'hui ?';
//     var options = JSON.parse(auyAutoChatOptions.chatbot_options || '{}');
//     var responses = JSON.parse(auyAutoChatOptions.responses || '{}');

//     function sendMessage(message, isUser = false) {
//         var messageClass = isUser ? 'user-message' : 'chatbot-message';
//         $('#chatbot-messages').append('<div class="' + messageClass + '"><div class="message">' + message + '</div></div>');
//         $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
//     }

//     function sendOptions(optionSet) {
//         var optionsHtml = '<div class="chatbot-options">';
//         $.each(optionSet, function(key, value) {
//             optionsHtml += '<button class="chatbot-option" data-value="' + key + '">' + value + '</button>';
//         });
//         optionsHtml += '</div>';
//         sendMessage(optionsHtml);
//     }

//     function resetChatbot() {
//         $('#chatbot-messages').html('');
//         sendMessage(welcomeMessage);
//         sendOptions(options);
//     }

//     sendMessage(welcomeMessage);
//     sendOptions(options);

//     var currentStep = 'initial';

//     function handleUserInput(userMessage) {
//         if (userMessage in responses) {
//             var response = responses[userMessage];
//             if (userMessage === 'suggerer_offres') {
//                 currentStep = 'offers';
//                 sendMessage(response);
//                 sendOptions({
//                     'particulier': 'Particulier',
//                     'entreprise': 'Entreprise'
//                 });
//             } else {
//                 sendMessage(response);
//                 currentStep = 'initial';
//                 sendOptions(options);
//             }
//         } else {
//             // Si le message n'est pas reconnu, envoyer à Wit.ai
//             $.ajax({
//                 url: auyAutoChatOptions.ajax_url, // URL pour les requêtes AJAX
//                 type: 'POST',
//                 data: {
//                     action: 'chatbot_process_message',
//                     message: userMessage
//                 },
//                 success: function(response) {
//                     var data = JSON.parse(response);
//                     if (data.intent) {
//                         if (data.intent in responses) {
//                             sendMessage(responses[data.intent]);
//                         } else {
//                             sendMessage('Intent détecté : ' + data.intent + '. Mais je n\'ai pas de réponse correspondante.');
//                         }
//                     } else {
//                         sendMessage(data.error || 'Une erreur s\'est produite.');
//                     }
//                     currentStep = 'initial';
//                     sendOptions(options);
//                 },
//                 error: function() {
//                     sendMessage('Une erreur s\'est produite lors de l\'envoi de votre message.');
//                     sendOptions(options);
//                 }
//             });
//         }
//     }

//     function sendTextMessage() {
//         var userMessage = $('#chatbot-input').val();
//         if (userMessage.trim() !== '') {
//             sendMessage(userMessage, true); // Afficher le message utilisateur
//             handleUserInput(userMessage); // Envoyer à handleUserInput
//             $('#chatbot-input').val(''); // Effacer le champ de saisie
//         }
//     }

//     // Événement pour le champ de saisie (touche Entrée)
//     $('#chatbot-input').on('keypress', function(e) {
//         if (e.which == 13) { // Touche Entrée
//             sendTextMessage();
//         }
//     });

//     // Événement pour le bouton d'envoi de message texte
//     $('#send-text-message').on('click', function() {
//         sendTextMessage();
//     });

//     // Placeholder pour le bouton d'envoi de message vocal
//     $('#send-voice-message').on('click', function() {
//         alert('Fonctionnalité de message vocal non encore implémentée.');
//     });

//     // Événement pour les options du chatbot
//     $('#chatbot-messages').on('click', '.chatbot-option', function() {
//         var userMessage = $(this).data('value');
//         var userText = $(this).text(); // Récupérer le texte de l'option cliquée
//         sendMessage(userText, true); // Afficher le texte de l'option comme message utilisateur
//         handleUserInput(userMessage); // Envoyer à handleUserInput
//     });

//     // S'assurer que le logo du chatbot existe avant de définir l'événement
//     if ($('#chatbot-logo').length) {
//         $('#chatbot-logo').on('click', function() {
//             $('#chatbot-container').toggleClass('hidden');
//         });
//     }

//     if ($('#chatbot-close').length) {
//         $('#chatbot-close').on('click', function() {
//             $('#chatbot-container').addClass('hidden');
//         });
//     }

//     // Ajouter l'événement de clic pour le bouton de réinitialisation
//     if ($('#reset-chatbot').length) {
//         $('#reset-chatbot').on('click', function() {
//             resetChatbot();
//         });
//     }
// });


