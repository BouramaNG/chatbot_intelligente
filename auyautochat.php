<?php
/*
Plugin Name: WawChatBot
Description: Plugin de chatbot pour le site WordPress
Version: 1.0
Author: Bourama Ngom
*/

// Ajouter le script et le style de chatbot à la page d'accueil
function auyautochat_enqueue_scripts() {
    wp_enqueue_script('auyautochat-script', plugin_dir_url(__FILE__) . 'auyautochat.js', array('jquery'), null, true);
    wp_enqueue_style('auyautochat-style', plugin_dir_url(__FILE__) . 'auyautochat.css');
}
add_action('wp_enqueue_scripts', 'auyautochat_enqueue_scripts');

// Inclure le fichier options.php pour les messages personnalisés
$options = include plugin_dir_path(__FILE__) . 'options.php';

// Passer les messages personnalisés au script JavaScript
function auyautochat_localize_script() {
    global $options;
    wp_localize_script('auyautochat-script', 'auyAutoChatOptions', array_merge($options, array(
        'ajax_url' => admin_url('admin-ajax.php')
    )));
}
add_action('wp_enqueue_scripts', 'auyautochat_localize_script');

// Ajouter une page d'options dans le tableau de bord
function auyautochat_menu() {
    add_options_page('AuyAutoChat Options', 'AuyAutoChat', 'manage_options', 'auyautochat', 'auyautochat_options_page');
}
add_action('admin_menu', 'auyautochat_menu');

function auyautochat_options_page() {
    ?>
    <div class="wrap">
        <h1>AuyAutoChat Options</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('auyautochat_options_group');
            do_settings_sections('auyautochat');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialiser les paramètres du plugin
function auyautochat_settings_init() {
    register_setting('auyautochat_options_group', 'auyautochat_options');
    
    add_settings_section(
        'auyautochat_section_developers',
        'Personnaliser le Chatbot',
        'auyautochat_section_developers_cb',
        'auyautochat'
    );

    add_settings_field(
        'auyautochat_field_welcome',
        'Message de bienvenue',
        'auyautochat_field_welcome_cb',
        'auyautochat',
        'auyautochat_section_developers'
    );

    add_settings_field(
        'auyautochat_field_options',
        'Options de Chatbot',
        'auyautochat_field_options_cb',
        'auyautochat',
        'auyautochat_section_developers'
    );
}
add_action('admin_init', 'auyautochat_settings_init');

function auyautochat_section_developers_cb() {
    echo '<p>Personnalisez les messages de votre chatbot ici.</p>';
}

function auyautochat_field_welcome_cb() {
    $options = get_option('auyautochat_options');
    ?>
    <input type="text" name="auyautochat_options[welcome_message]" value="<?php echo esc_attr($options['welcome_message']); ?>" />
    <?php
}

function auyautochat_field_options_cb() {
    $options = get_option('auyautochat_options');
    ?>
    <textarea name="auyautochat_options[chatbot_options]" rows="10" cols="50"><?php echo esc_textarea($options['chatbot_options']); ?></textarea>
    <?php
}

// Injecter le conteneur HTML du chatbot dans le pied de page
function auyautochat_add_chatbot_container() {
    ?>
    <div id="chatbot-logo">
        <img src="<?php echo plugin_dir_url(__FILE__) . 'waw.jpg'; ?>" alt="Chatbot Logo" />
    </div>
    <div id="chatbot-container" class="hidden">
        <div id="chatbot-header">
            <h5>WawBot</h5>
            <button id="reset-chatbot">🔄</button> <!-- Icône de réinitialisation -->
            <button id="chatbot-close">&times;</button>
        </div>
        <div id="chatbot-messages">
            <!-- Les messages du chatbot seront ajoutés ici -->
        </div>
        <div id="chatbot-input-container">
            <input type="text" id="chatbot-input" placeholder="Tapez votre message ici..." />
            <button id="send-text-message" class="message-button">✉️</button>
        </div>
    </div>

    <!-- Ajouter le script Voiceflow -->
    <script type="text/javascript">
  (function(d, t) {
      var v = d.createElement(t), s = d.getElementsByTagName(t)[0];
      v.onload = function() {
        window.voiceflow.chat.load({
          verify: { projectID: '' },//ici lID
          url: '',//mettez ici lurl
          versionID: 'production'
        });
      }
      v.src = "https://cdn/widget/bundle.mjs"; v.type = "text/javascript"; s.parentNode.insertBefore(v, s);
  })(document, 'script');
</script>
    <?php
}
add_action('wp_footer', 'auyautochat_add_chatbot_container');

// Fonction pour appeler Voiceflow
function call_voiceflow($message) {
    $api_url = ''; // Remplacez par votre 
    $data = json_encode(array('type' => 'text', 'payload' => $message));
    $args = array(
        'body'        => $data,
        'headers'     => array(
            'Content-Type' => 'application/json',
            'Authorization' => '' 
        ),
        'method'      => 'POST',
        'data_format' => 'body'
    );

    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    return $result[0]['text'] ?? 'Je suis désolé, je ne comprends pas votre question.';
}

// Ajouter un endpoint pour les requêtes AJAX
add_action('wp_ajax_nopriv_auyautochat_send_message', 'auyautochat_send_message');
add_action('wp_ajax_auyautochat_send_message', 'auyautochat_send_message');

function auyautochat_send_message() {
    $message = sanitize_text_field($_POST['message']);
    $predefined_responses = include plugin_dir_path(__FILE__) . 'options.php';
    $response = '';

    // Vérifier si le message a une réponse prédéfinie
    if (isset($predefined_responses['responses'][$message])) {
        $response = $predefined_responses['responses'][$message];
    } else {
        // Envoyer la question à Voiceflow si elle n'est pas prédéfinie
        $response = call_voiceflow($message);
    }

    if ($response === FALSE) {
        wp_send_json_error('Erreur lors de la communication avec .');
    }

    wp_send_json_success($response);
}


?>
