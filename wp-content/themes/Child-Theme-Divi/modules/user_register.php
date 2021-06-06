<?php
// create_user_directory()
// set_membership_and_date()

/**************************************************************************************************
 *								FUNCTION create_user_directory()								  *
 **************************************************************************************************/

// Runs when a user's profile is first created. Action function argument: user ID.
add_action('user_register','create_user_directory');

/**
 * Create directory to store uploaded files
 *
 * @param [type] $user_id
 * @return void
 */
function create_user_directory($user_id) {

	$cn_user = get_user_by( 'id', $user_id ); 
	$cn_user->user_login;
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/' . $cn_user->user_login;

	if (! is_dir($upload_dir)) {
	   mkdir( $upload_dir, 0755 );
	}
} // function create_user_directory()




/**************************************************************************************************
 *								FUNCTION set_membership_and_date()								  *
 **************************************************************************************************/

// Runs when a user's profile is first created. Action function argument: user ID.
add_action('user_register','set_membership_and_date');

/**
 * Set the user_registered date dans users et membership_product data dans usermeta
 *
 * @param [type] $user_id
 * @return void
 */
function set_membership_and_date($user_id) {

	date_default_timezone_set('Europe/Paris');
	$subsription_date = date('Y-m-d H:i:s');
	update_user_meta($user_id,'membership_product_date',$subsription_date);
	update_user_meta($user_id,'membership_product_id','238609');

	global $wpdb;
    $users = $wpdb->prefix . 'users';
    $new_registered_date = array('user_registered' => $subsription_date);
    $response_date = iUpdateArrayMultiConds(
        $users, 
        $new_registered_date, 
        array('id' => $user_id)
    );
    $response_date = json_decode($response_date);
} // function set_membership_and_date()