# chatbot_intelligente
# WawChatBot

WawChatBot est un plugin WordPress permettant d'intégrer un chatbot sur votre site web. Il offre la possibilité de configurer des messages personnalisés et d'interagir avec l'API Voiceflow pour des réponses dynamiques.

## Table des Matières

- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Personnalisation](#personnalisation)
- [Support](#support)

## Installation

1. **Téléchargez le plugin** :
   - Clonez ce dépôt GitHub ou téléchargez l'archive ZIP.

2. **Installez le plugin sur WordPress** :
   - Accédez à votre tableau de bord WordPress.
   - Allez dans **Extensions > Ajouter**.
   - Cliquez sur **Téléverser une extension** et choisissez le fichier ZIP téléchargé.
   - Activez le plugin après l'installation.

## Configuration

1. **Configurer l'environemment** :
   - Ouvrez le fichier `auyautochat.js` et remplacez les valeurs suivantes :
     ```javascript
     window.voiceflow.chat.load({
       verify: { projectID: 'YOUR_PROJECT_ID' },
       url: 'YOUR_API_URL',
       versionID: 'production'
     });
     ```

2. **Configurer les Messages Prédéfinis** :
   - Ouvrez le fichier `options.php` et ajoutez vos messages prédéfinis dans le tableau `responses`.

## Utilisation

Une fois le plugin activé et configuré, le chatbot apparaîtra sur la page d'accueil de votre site. Vous pouvez interagir avec lui en envoyant des messages et en recevant des réponses basées sur les configurations prédéfinies ou dynamiques via l'API Voiceflow.

## Personnalisation

1. **Personnaliser le Message de Bienvenue et les Options** :
   - Accédez à **Réglages > AuyAutoChat** dans votre tableau de bord WordPress.
   - Modifiez les champs "Message de bienvenue" et "Options de Chatbot" selon vos besoins.

2. **Modifier le Style du Chatbot** :
   - Modifiez le fichier `auyautochat.css` pour personnaliser l'apparence du chatbot.

3. **Ajouter des Scripts Personnalisés** :
   - Ajoutez des scripts personnalisés dans le fichier `auyautochat.js` si nécessaire.

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur ce dépôt GitHub. Nous serons ravis de vous aider.

---

Créé par Bourama Ngom
