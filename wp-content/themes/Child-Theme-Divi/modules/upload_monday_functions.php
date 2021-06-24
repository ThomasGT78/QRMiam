<?php
// insert_user_registered()
// upload_profil_user()
// upload_user_registered()
// upload_user_statut()




/****************************************************************************************************
*								FUNCTION insert_user_registered()									*
****************************************************************************************************/

add_action('user_register','insert_user_registered');

function insert_user_registered($user_id) {
    
            /*********************
            *   GET USER DATA    *
            *********************/
    
    $data_user = get_user_by( 'id', $user_id ); 
    $user_login = $data_user->user_login;

    // url
    $user_login_url = str_replace(" ", "%20", $user_login);
    $url = "https://qrmiam.fr/profile/?username=".$user_login_url;

    // Get data from users table
    $users_table_data = get_users_data_forMonday_By_userLogin($user_login);
    
    $registered_date = $users_table_data['user_registered'];
    $email = $users_table_data['user_email'];

    // Get data from usermeta table
    $usermeta_table_data = get_user_meta($user_id);

    // Edit Statut
    $membership_product_id = $usermeta_table_data['membership_product_id'][0];
    $list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10, '3000' => 1);
    $statut = $list_statut[$membership_product_id];
    
    $membership_product_date = $usermeta_table_data['membership_product_date'][0];
 
            /*****************
            *   DO ACTION    *
            *****************/
    
    $responseContent = create_new_user($user_login, $user_id, $registered_date, $url, $statut, $membership_product_date, $email);
    
    $monday_item_id = $responseContent['data']['create_item']['id'];
    update_user_meta($user_id,'monday_item_id',$monday_item_id);
    
} // END insert_user_registered()



/****************************************************************************************************
*									FUNCTION upload_profil_user()									*
****************************************************************************************************/
// Update some data into Monday after the user update his profile with a form

add_action('profile_update','upload_profil_user');

function upload_profil_user() {

    if(isset($_POST['account_email'])) {
        echo "Modif email OK<br>";
    }
    $user_id = get_current_user_id();    

    // Get email
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
    
    // Get data from usermeta table
    $usermeta_table_data = get_user_meta($user_id);

    // Item ID Monday
    $monday_item_id = $usermeta_table_data['monday_item_id'][0];

    // Tel
    $phone = $usermeta_table_data['user_registration_tel'][0];

    // Statut
    $membership_product_id = $usermeta_table_data['membership_product_id'][0];
    $list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10, '3000' => 1);
    $statut = $list_statut[$membership_product_id];

    // Date de souscription
    $membership_product_date = $usermeta_table_data['membership_product_date'][0];

    
    update_user_info($monday_item_id, $statut, $membership_product_date, $email, $phone);

}

/****************************************************************************************************
*									FUNCTION upload_profil_user()									*
****************************************************************************************************/
// Update some data into Monday after the user go on the page to manage documents or when someone visit his public page


function upload_profil_user_public($user_id) {

    // Get email
    $current_user = get_userdata($user_id);
    $email = $current_user->user_email;
    
    // Get data from usermeta table
    $usermeta_table_data = get_user_meta($user_id);

    // Item ID Monday
    $monday_item_id = $usermeta_table_data['monday_item_id'][0];

    // Tel
    $phone = $usermeta_table_data['user_registration_tel'][0];

    // Statut
    $membership_product_id = $usermeta_table_data['membership_product_id'][0];
    $list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10, '3000' => 1);
    $statut = $list_statut[$membership_product_id];

    // Date de souscription
    $membership_product_date = $usermeta_table_data['membership_product_date'][0];

    
    update_user_info($monday_item_id, $statut, $membership_product_date, $email, $phone);

}

/****************************************************************************************************
*								FUNCTION upload_user_registered()									*
****************************************************************************************************/
// Insert a new line in Monday after a vistor register on the web site

add_action('wp_login','upload_user_registered');

function upload_user_registered($user_login) {
    
    $user_id = get_userID_By_username($user_login);

    // Get data from usermeta table
    $usermeta_table_data = get_user_meta($user_id);

    // Item ID Monday
    $monday_item_id = $usermeta_table_data['monday_item_id'][0];

    // Statut
    $membership_product_id = $usermeta_table_data['membership_product_id'][0];
    $list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10, '3000' => 1);

    $statut = $list_statut[$membership_product_id];

    $membership_product_date = $usermeta_table_data['membership_product_date'][0];

    // Etablissement
    $etablissement = $usermeta_table_data['user_registration_nom_etablissement'][0];

    // Type
    $list_type = array('Agence Web' => 7, 'Autre' => 5, 'Bar' => 2, 'Café, salon de thé' => 3, 'Consultant' => 8, 'Hôtel' => 10, 'Hôtel / Restaurant / Brasserie' => 11, 'Hôtel, Restaurant et Brasserie' => 11, 'Night-Club' => 4, 'Restaurant' => 9);

    $type = $usermeta_table_data['user_registration_check_box_1591092840'][0];
    $type = $list_type[$type];

    // Tel
    $phone = $usermeta_table_data['user_registration_tel'][0];

    // Lieu and Code Postal
    $rue = $usermeta_table_data['user_registration_adresse_rue'][0];
    $ville = $usermeta_table_data['user_registration_adresse_ville'][0];
    $code_postal = $usermeta_table_data['user_registration_adresse_codepostal'][0];

    $lieu = "$rue, $ville, $code_postal";
    
            /*****************
            *   DO ACTION    *
            *****************/
    
    update_logged_user($monday_item_id, $etablissement, $statut, $membership_product_date, $lieu, $code_postal, $type, $phone);

} // END upload_user_registered()




/****************************************************************************************************
*								FUNCTION upload_user_statut()   									*
****************************************************************************************************/

/**
 * Uplaod Statut and date of subscription (in mySQL and Monday) after payment of membership product
 */
add_action('woocommerce_payment_complete','upload_user_statut');

function upload_user_statut() {

    $user_id = get_current_user_id();

    date_default_timezone_set('Europe/Paris');
	$subsription_date = date('Y-m-d H:i:s');
    update_user_meta($user_id,'membership_product_date',$subsription_date);

    // Get data from usermeta table
    $usermeta_table_data = get_user_meta($user_id);

    // Item ID Monday
    $monday_item_id = $usermeta_table_data['monday_item_id'][0];

    // Statut
    $membership_product_id = $usermeta_table_data['membership_product_id'][0];
    $list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10, '3000' => 1);

    $statut = $list_statut[$membership_product_id];

    update_membership_id_and_date_monday($monday_item_id, $statut, $subsription_date);
}


