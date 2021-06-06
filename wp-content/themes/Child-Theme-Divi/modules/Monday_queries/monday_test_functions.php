<?php

// create_new_user_full()
// create_new_user_static()
// create_new_user()
// create_item_basic()
// update_item_monday()
// monday_update_statut()

// get_item_id_by_name()
// get_item_id_by_name_min()
// get_item_id_by_user_id()

// update_id_date_monday()
// harmonize_phone_number()
// harmonize_name_etablishment()
// harmonize_type()

// update_all_users_monday()
// update_all_id_monday_in_database()

/****************************************************************************************************
*								        MONDAY TEST FUNCTIONS										*
****************************************************************************************************/

/****************************************************************************************************
*						        FUNCTIONS create_new_user_full()									*
*	                                                        										*
*	Insert new item (user) in Monday after user registration	(not used)							*
****************************************************************************************************/

function create_new_user_full ($user_login, $user_id, $registered_date, $url, $etablissement, $statut, $lieu, $code_postal, $type, $email, $phone) {

    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation { 
        create_item (
            board_id:1190493010, 
            item_name:"'.$user_login.'" 
            column_values:"{
                \"user_id\": \"'.$user_id.'\",
                \"date\": \"'.$registered_date.'\",
                \"lien_internet\": \"'.$url.' '.$url.'\",
                \"texte4\": \"'.$etablissement.'\",
                \"statut8\": {\"index\": '.$statut.'},
                \"lieu\": \"48.814965569283345 2.33097051228017 '.$lieu.'\",
                \"code_postal\": \"'.$code_postal.'\",
                \"menu_d_roulant\": \"'.$type.'\",
                \"e_mail\": \"'.$email.' '.$email.'\",
                \"t_l_phone\": \"'.$phone.'\"
            }"
        ) { 
            id 
        } 
    }';
    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));

    return $responseContent = json_decode($data, true);

    // echo json_encode($responseContent);
}



/****************************************************************************************************
*						        FUNCTION create_new_user_static()									*
****************************************************************************************************/
function create_new_user_static ($item_name, $id, $date, $url, $etablissement, $statut, $lieu, $code_postal, $type, $email, $phone ) {

    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    
    $query = 'mutation { 
        create_item (
            board_id:1190493010, 
            item_name:"'.$item_name.'" 
            column_values:"{
                \"user_id\": \"40\",
                \"date\": \"2010-05-10\",
                \"lien_internet\": \"https://qrmiam.fr/profile/?username=test https://qrmiam.fr/profile/?username=test\",
                \"texte4\": \"Casa de Tom\",
                \"statut8\": {\"index\": 1},
                \"lieu\": \"48.814965569283345 2.33097051228017 6 Mail des Tilleuls, Gentilly\",
                \"code_postal\": \"94250\",
                \"menu_d_roulant\": \"4\",
                \"e_mail\": \"thomas.garot@gmail.com thomas.garot@gmail.com\",
                \"t_l_phone\": \"0641044068 FR\"
            }"
        ) { 
            id 
        } 
    }';
    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));

    return $responseContent = json_decode($data, true);

    // echo json_encode($responseContent);
}



/****************************************************************************************************
*							        FUNCTION create_item_basic()										*
****************************************************************************************************/

function create_item_basic ($item_name) {

    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = 'mutation{ 
        create_item (
            board_id:1190493010, 
            item_name:"'.$item_name.'"
        ) { 
            id 
        } 
    }';
    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));

    return $responseContent = json_decode($data, true);

    // echo json_encode($responseContent);
}




/****************************************************************************************************
*							        FUNCTION update_item_monday()									*
****************************************************************************************************/

function update_item_monday ($user_login) {

    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = 'mutation {
        change_simple_column_value (
            board_id: 1190493010, 
            item_id: 1284855889, 
            column_id: "texte4", 
            value: value: "'.$user_login.'"
        ) {
            id
        }
    }';

    $data = @file_get_contents($apiUrl, false, stream_context_create([
    'http' => [
    'method' => 'POST',
    'header' => $headers,
    'content' => json_encode(['query' => $query]),
    ]
    ]));
    
    return $responseContent = json_decode($data, true);

    // echo json_encode($responseContent);
}



/****************************************************************************************************
*							        FUNCTION monday_update_statut()										*
****************************************************************************************************/

function monday_update_statut ($user_monday_id, $statut) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = 'mutation {
        change_simple_column_value (
            board_id: 609995067, 
            item_id: '.$user_monday_id.', 
            column_id: "statut8", 
            value: "'.$statut.'"
        ) {
            id
        }
    }';

    $data = @file_get_contents($apiUrl, false, stream_context_create([
    'http' => [
    'method' => 'POST',
    'header' => $headers,
    'content' => json_encode(['query' => $query]),
    ]
    ]));

    return $responseContent = json_decode($data, true);

    // return json_encode($responseContent);
}




/****************************************************************************************************
*							        FUNCTION get_item_id_by_name()									*
****************************************************************************************************/

function get_item_id_by_name ($user_login) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = '{
        items_by_column_values (
            board_id: 1190493010, 
            column_id: "name", 
            column_value: "'.$user_login.'"
        ) {
            id
            name
            column_values{
                id
                text
              }
        }}';

    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));
    return $responseContent = json_decode($data, true);

    // return json_encode($responseContent);
}

/****************************************************************************************************
*							        FUNCTION get_item_id_by_name_min()									*
****************************************************************************************************/

function get_item_id_by_name_min ($user_login) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = '{
        items_by_column_values (
            board_id: 609995067, 
            column_id: "name", 
            column_value: "'.$user_login.'"
        ) {
            id
        }}';

    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));
    return $responseContent = json_decode($data, true);

}


/****************************************************************************************************
*							        FUNCTION get_item_id_by_user_id()									*
****************************************************************************************************/

function get_item_id_by_user_id ($user_id) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = '{
        items_by_column_values (
            board_id: 609995067, 
            column_id: "user_id", 
            column_value: "'.$user_id.'"
        ) {
            id
        }}';

    $data = @file_get_contents($apiUrl, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
    ]));
    return $responseContent = json_decode($data, true);

}

/****************************************************************************************************
*						        FUNCTIONS update_id_date_monday()   								*
*	                                                        										*
*	Update user_id and membership_product_date on an item(user) in Monday       					*
****************************************************************************************************/

function update_id_date_monday ($user_monday_id, $user_id, $membership_product_date) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation {
        change_multiple_column_values (
            board_id: 609995067, 
            item_id: '.$user_monday_id.', 
            column_values: "{
                \"user_id\": \"'.$user_id.'\",
                \"date5\": \"'.$membership_product_date.'\"
            }"
        ) {
            id
        }
    }';
    
    $data = @file_get_contents($apiUrl, false, stream_context_create([
     'http' => [
     'method' => 'POST',
     'header' => $headers,
     'content' => json_encode(['query' => $query]),
     ]
    ]));

    return $responseContent = json_decode($data, true);
    
}



/****************************************************************************************************
*							        FUNCTION harmonize_phone_number()								*
****************************************************************************************************/

function harmonize_phone_number() {
    global $wpdb;
    $usermeta = $wpdb->prefix . 'usermeta';
    $users = $wpdb->prefix . 'users';
    
    $sql = "SELECT ID FROM $users;";

    $list_user = iWhileFetch($sql);

    function change_phone_num ($phone) {
        $nb_num = strlen($phone);
        $zero = "0";
        $start_with_zero = substr( $phone, 0, 1 ) == $zero;
        if($nb_num == 9 AND !$start_with_zero){
            $phone = "0".$phone;
        } elseif($nb_num > 10 AND !$start_with_zero){
            $phone = "+".$phone;
        }
        return $phone;
    }

    foreach ($list_user as $user) {
        $user_id = $user['ID'];
        $usermeta_table_data = get_user_meta($user_id);
        
        if ($usermeta_table_data['user_registration_number_box_1592387390704']) {
            $phone = $usermeta_table_data['user_registration_number_box_1592387390704'][0];
            $new_phone = change_phone_num($phone);
            update_user_meta($user_id,'user_registration_tel',$new_phone);
            delete_user_meta($user_id, 'user_registration_number_box_1592387390704');
        } elseif ($usermeta_table_data['user_registration_tel']) {
            $phone = $usermeta_table_data['user_registration_tel'][0];
            $new_phone = change_phone_num($phone);
            update_user_meta($user_id,'user_registration_tel',$new_phone);
        }
    }

}



/****************************************************************************************************
*							        FUNCTION harmonize_address()								*
****************************************************************************************************/

function harmonize_name_etablishment() {
    global $wpdb;
    $usermeta = $wpdb->prefix . 'usermeta';
    $users = $wpdb->prefix . 'users';
    
    $sql = "SELECT ID FROM $users;";

    $list_user = iWhileFetch($sql);


    foreach ($list_user as $user) {
        $user_id = $user['ID'];
        $usermeta_table_data = get_user_meta($user_id);
        
        if ($usermeta_table_data['user_registration_input_box_1591092586428']) {

            $name = $usermeta_table_data['user_registration_input_box_1591092586428'][0];

            update_user_meta($user_id,'user_registration_nom_etablissement',$name);
            delete_user_meta($user_id, 'user_registration_input_box_1591092586428');

        } 
    }

}



/****************************************************************************************************
*						                FUNCTION harmonize_type()       							*
****************************************************************************************************/

function harmonize_type() {
    global $wpdb;
    $usermeta = $wpdb->prefix . 'usermeta';
    $users = $wpdb->prefix . 'users';
    
    $sql = "SELECT ID FROM $users;";

    $list_user = iWhileFetch($sql);

    function unserialize_type ($type) {
        $a = "a:";
        $is_serialised = substr( $type, 0, 2 ) == $a;
        
        if ($is_serialised){
           return $unserialized_type = unserialize($type)[0];
        }
    }

    foreach ($list_user as $user) {
        $user_id = $user['ID'];
        $usermeta_table_data = get_user_meta($user_id);
        
        if ($usermeta_table_data['user_registration_check_box_1591092840']) {
            $type = $usermeta_table_data['user_registration_check_box_1591092840'][0];

            $a = "a:";
            $is_serialised = substr( $type, 0, 2 ) == $a;
            if ($is_serialised){
                $unserialized_type = unserialize($type)[0];
                update_user_meta($user_id,'user_registration_check_box_1591092840',$unserialized_type);
            }
        }
    }

}



/****************************************************************************************************
*								FUNCTION update_all_users_monday()									*
****************************************************************************************************/
// NO USED ANY MORE, ONLY FOR DEVELPMENT
function update_all_users_monday() {
    global $wpdb;
    $usermeta = $wpdb->prefix . 'usermeta';
    $users = $wpdb->prefix . 'users';

    $sql = "SELECT 
        u.ID, u.user_login, m.meta_value 
        FROM $users u
        INNER JOIN $usermeta m
        ON u.ID = m.user_id
        WHERE m.meta_key = 'membership_product_date'
    ;";
    // $sql = "SELECT 
    //     ID, user_login 
    //     FROM $users
    // ;";

    $list_user = iWhileFetch($sql);
    // print_r($list_user);

    foreach ($list_user as $user) {
        $user_id = $user['ID'];
        $user_login = $user['user_login'];
        $membership_product_date = $user['meta_value'];
        
        if ($user_id == 436) {
            $user_monday_raw_data_id = get_item_id_by_name_min ($user_login);
            $user_monday_id = $user_monday_raw_data_id["data"]["items_by_column_values"][0]["id"];
            // echo $user_monday_id."<br>";
            update_id_date_monday($user_monday_id, $user_id, $membership_product_date);
            update_user_meta($user_id,'monday_item_id',$user_monday_id);
        }
    }
}


/****************************************************************************************************
*							FUNCTION update_all_id_monday_in_database()								*
****************************************************************************************************/

function update_all_id_monday_in_database() {
    global $wpdb;
    $usermeta = $wpdb->prefix . 'usermeta';
    $users = $wpdb->prefix . 'users';

    
    $sql = "SELECT 
        u.ID, u.user_login, m.meta_value 
        FROM $users u
        INNER JOIN $usermeta m
        ON u.ID = m.user_id
        WHERE m.meta_key = 'membership_product_date'
    ;";

    $list_user = iWhileFetch($sql);
    // print_r($list_user);

    foreach ($list_user as $user) {
        $user_id = $user['ID'];
        // $user_login = $user['user_login'];
        // $membership_product_date = $user['meta_value'];
        
        if ($user_id >= 50 AND $user_id <= 150) {
            $user_monday_raw_data_id = get_item_id_by_user_id ($user_id);
            $user_monday_id = $user_monday_raw_data_id["data"]["items_by_column_values"][0]["id"];
            // echo $user_monday_id."<br>";
            // update_id_date_monday($user_monday_id, $user_id, $membership_product_date);
            update_user_meta($user_id,'monday_item_id',$user_monday_id);
        }
    }
}

/****************************************************************************************************
*								FUNCTION upload_user_statut()									*
****************************************************************************************************/

/**
 * Uplaod Statut and date of subscription (in mySQL and Monday) after payment of membership product
 */
// add_action('woocommerce_payment_complete','upload_user_statut');

function upload_user_statut_test($user_id) {

    // $user_id = get_current_user_id();

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
