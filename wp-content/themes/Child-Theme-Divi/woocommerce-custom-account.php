<?php

/****    PERSONNALISATION WOOCOMMERCE    ****/
// MON COMPTE

// Cacher les tabs Adresse et méthode de paiement pour les afficher dans les détails du compte :
// 1. First, hide the tab that needs to be merged/moved (edit-address in this case)
add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 999 );
 
function bbloomer_remove_address_my_account( $items ) {
   unset( $items['edit-address'] );
   return $items;
}

add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_payment_methods_my_account', 999 );
 
function bbloomer_remove_payment_methods_my_account( $items ) {
   unset( $items['payment-methods'] );
   return $items;
}

add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_subscriptions_my_account', 999 );
 
function bbloomer_remove_subscriptions_my_account( $items ) {
   unset( $items['subscriptions'] );
   return $items;
}

// WOOCOMMERCE MY ACCOUNT : print the ex tab content (woocommerce_account_edit_address) into an existing tab (woocommerce_account). See notes below!

add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_address' );
add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_payment_methods' );
// add_action( 'woocommerce_account_orders_endpoint', array(new WCS_Query(),'endpoint_content' ) );


//Ajout des titres aux sections

//Vos adresses
add_action( 'woocommerce_before_edit_account_address_form', 'addTitleAddress', 10 );
function addTitleAddress () { ?>
    <h2>Vos adresses</h2>
<?php
}


//Vos moyens de paiement
add_action( 'woocommerce_before_account_payment_methods', 'addTitlePayment', 10 );
function addTitlePayment () { ?>
    <h2>Vos moyen de paiement</h2>
<?php
}

//Vos informations personelles
add_action( 'woocommerce_before_edit_account_form', 'addTitleInformation', 10 );
function addTitleInformation () { ?>
    <h2>Vos informations personnelles :</h2>
<?php 
}

//Vos abonnements actifs
add_action( 'woocommerce_after_account_orders', 'addTitleAbonnement', 10 );
function addTitleAbonnement () { ?>
    <h2>Vos abonnements actifs :</h2>
<?php
}

//Vos commandes
add_action( 'woocommerce_before_account_orders', 'addTitleCommand', 10 );
function addTitleCommand () { ?>
    <h2>Vos commandes :</h2>
<?php
}

//Création des points de terminaisons WOOCOMMERCE

//Qrcode
//Création du ENDPOINT
 add_action( 'init', 'my_account_new_endpoints' );

 function my_account_new_endpoints() {
 	add_rewrite_endpoint( 'qrcode', EP_ROOT | EP_PAGES );
 }
 
 // Récupération du template
 add_action( 'woocommerce_account_qrcode_endpoint', 'qrcode_endpoint_content' );
 function qrcode_endpoint_content() {
     echo do_shortcode( '[gen-qrcode]');
 }
 
 //Upload
//Création du ENDPOINT
 add_action( 'init', 'my_account_new_endpoints_2' );

 function my_account_new_endpoints_2() {
 	add_rewrite_endpoint( 'upload', EP_ROOT | EP_PAGES );
 }
 
 // Récupération du template
 add_action( 'woocommerce_account_upload_endpoint', 'upload_endpoint_content' );
 function upload_endpoint_content() {
     echo do_shortcode( '[user_document_upload]');
 }
 
 //Statistiques
//Création du ENDPOINT
 add_action( 'init', 'my_account_new_endpoints_3' );

 function my_account_new_endpoints_3() {
 	add_rewrite_endpoint( 'statistique', EP_ROOT | EP_PAGES );
 }
 
 // Récupération du template
 add_action( 'woocommerce_account_statistique_endpoint', 'statistique_endpoint_content' );
 function statistique_endpoint_content() {
	 echo do_shortcode( '[user_basic_statistics]');
     echo do_shortcode( '[advanced_statistics]');
 }
 
   //Proposer le téléchargement de la notice pour les abonnés via leur espace
add_action( 'woocommerce_before_my_account', 'addTitleDoc', 10 );
function addTitleDoc () {
   echo "<h3>Besoin d'aide ? Consulter la documentation <a href='https://qrmiam.fr/aide/' style='color: red;'>ici</a></h3>";
}

// Menu de connexion - Affichage user loged in

add_action('woocommerce_before_customer_login_form', 'menuDisplay');
function menuDisplay () {
   if ( is_user_logged_in() ) {
    echo "<div id='menu' role='navigation'> <?php wp_nav_menu(array('theme_location' => 'accountMobile')); ?> </div>";
 }
}

