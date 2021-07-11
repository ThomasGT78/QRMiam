<?php
/**
** activation theme
**/

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
 wp_enqueue_style( 'Divi', get_template_directory_uri() . '/style.css' );

}

// UPDATE PHONE NUMBER FROM CUSTOM FIELD
add_action( 'woocommerce_save_account_details', 'save_phone_number', 12, 1 );
function save_phone_number($user_id) {
    // For Favorite color
    if(isset($_POST['account_tel'])) {
        update_user_meta($user_id, 'user_registration_tel', sanitize_text_field($_POST['account_tel']));
	}
}


// get_template_part( 'shivam-functions' );
// get_template_part( 'shivam-functions-tom' );
get_template_part( 'qrmiam-functions' );


get_template_part( 'woocommerce-custom-account' );

/****    LISTE DES HOOKS    ****/

// user_document_upload => /upload
// user_public_page => /profile/?username=
// membership_product => /abonnement
// gen-qrcode => /qrcode


// add_action('wp_enqueue_scripts', 'MyThemeScriptAndStyles');

function MyThemeScriptAndStyles(){
    // L'uri du thème sur lequel on se trouve
    // $uri = get_theme_file_uri();
    $uri = get_template_directory_uri();

    // Ajout d'une feuille de style
    wp_enqueue_style(
        "upload-styles", 
        $uri."/css/upload_style.css");  

    wp_enqueue_script(
        "j-query", 
        "https://code.jquery.com/jquery-1.7.2.min.js"  // Tableau des dépendances, charge jquery avant bootstrap-js
    );
}
// hook pour l'exécution de la fonction