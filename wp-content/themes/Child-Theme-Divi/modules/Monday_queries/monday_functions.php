<?php
// create_new_user()
// update_logged_user()
// update_etablissement_name()
// update_id_date_monday()

/****************************************************************************************************
*								        MONDAY FUNCTIONS											*
****************************************************************************************************/

/****************************************************************************************************
*							        FUNCTIONS create_new_user()										*
*	                                                        										*
*	Insert new item (user) in Monday after user registration										*
****************************************************************************************************/
function create_new_user($user_login, $user_id, $registered_date, $url, $statut, $membership_product_date, $email) {

    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation { 
        create_item (
            board_id:609995067, 
            item_name:"'.$user_login.'" 
            column_values:"{
                \"user_id\": \"'.$user_id.'\",
                \"date\": \"'.$registered_date.'\",
                \"lien_internet\": \"'.$url.' '.$url.'\",
                \"statut8\": {\"index\": '.$statut.'},
                \"date5\": \"'.$membership_product_date.'\",
                \"e_mail\": \"'.$email.' '.$email.'\"
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
*						        FUNCTIONS update_logged_user()  									*
*	                                                        										*
*	Update item (user) in Monday after user login                       							*
****************************************************************************************************/

function update_logged_user ($monday_item_id, $etablissement, $statut, $membership_product_date, $lieu, $code_postal, $type, $phone) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation {
        change_multiple_column_values (
            board_id: 609995067, 
            item_id: '.$monday_item_id.', 
            column_values: "{
                \"texte4\": \"'.$etablissement.'\",
                \"statut8\": {\"index\": '.$statut.'},
                \"date5\": \"'.$membership_product_date.'\",
                \"lieu\": \"48.814965569283345 2.33097051228017 '.$lieu.'\",
                \"code_postal\": \"'.$code_postal.'\",
                \"menu_d_roulant\": \"'.$type.'\",
                \"t_l_phone\": \"'.$phone.' FR\"
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
    $responseContent = json_decode($data, true);
    
    return json_encode($responseContent);
    
}
    


/****************************************************************************************************
*						        FUNCTIONS update_logged_user()  									*
*	                                                        										*
*	Update item (user) in Monday after user login                       							*
****************************************************************************************************/

function update_user_info ($monday_item_id, $statut, $membership_product_date, $email, $phone) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation {
        change_multiple_column_values (
            board_id: 609995067, 
            item_id: '.$monday_item_id.', 
            column_values: "{
                \"statut8\": {\"index\": '.$statut.'},
                \"date5\": \"'.$membership_product_date.'\",
                \"e_mail\": \"'.$email.' '.$email.'\",
                \"t_l_phone\": \"'.$phone.' FR\"
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
    $responseContent = json_decode($data, true);
    
    return json_encode($responseContent);
    
}
   


/****************************************************************************************************
*							       FUNCTION update_etablissement_name() 							*
****************************************************************************************************/

function update_etablissement_name ($etablishment_name, $user_monday_id) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

    $query = 'mutation {
        change_simple_column_value (
            board_id: 609995067, 
            item_id: '.$user_monday_id.', 
            column_id: "texte4", 
            value: "'.$etablishment_name.'"
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
*						FUNCTIONS update_membership_id_and_date_monday()   							*
*	                                                        										*
*	Update user_id and membership_product_date on an item(user) in Monday       					*
****************************************************************************************************/

function update_membership_id_and_date_monday ($monday_item_id, $statut, $membership_product_date) {
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjQ4Mzc3MDM0LCJ1aWQiOjEyNjU5Mzg5LCJpYWQiOiIyMDIwLTA2LTA4VDEwOjQwOjA5LjAwMFoiLCJwZXIiOiJtZTp3cml0ZSIsImFjdGlkIjo1Njc0Mjk0LCJyZ24iOiJ1c2UxIn0.xNzGee_xepUBxc73pA1m7JaYT_wlBKXszoHa0WsqAyI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    $query = 'mutation {
        change_multiple_column_values (
            board_id: 609995067, 
            item_id: '.$monday_item_id.', 
            column_values: "{
                \"statut8\": {\"index\": '.$statut.'},
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

