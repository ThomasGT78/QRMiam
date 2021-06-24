<?php

// get_users_data_forMonday_By_userLogin($user_login)
// get_usermeta_by_ID($user_id)
// get_userID_By_username()
// get_userID_and_username()

// get_user_display_name_By_Id()
// get_user_registered_By_Id()
// get_etablissement_color_By_Id()
// get_etablissement_color_By_username()

// get_all_user_docs_By_Id()
// get_user_banner_By_Id()
// get_user_documents_By_Id()
// get_docs_path_By_docs_id()
// insert_user_doc_By_Id()

/**************************************************************************************************
 *							FUNCTION get_users_data_forMonday_By_userLogin()					  *
 **************************************************************************************************/

function get_users_data_forMonday_By_userLogin($user_login) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "SELECT ID, user_registered, user_email FROM `".$users."` WHERE `user_login`=\"$user_login\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	$users_data_raw = iWhileFetch($users_sql);
	// print_r($user_id_raw);
	return $users_data = $users_data_raw[0];

} // get_users_data_forMonday_By_userLogin()




/**************************************************************************************************
 *							FUNCTION get_usermeta_by_ID()						  *
 **************************************************************************************************/

function get_usermeta_by_ID($user_id) {
	global $wpdb;
	$usermeta = $wpdb->prefix . 'usermeta';
	//$username="victor_test";

	$usermeta_sql = "SELECT meta_key, meta_value FROM `".$usermeta."` WHERE `user_id`=\"$user_id\"";
	
	$usermeta_data_raw = iWhileFetch($usermeta_sql);

	$usermeta_data_table = [];
	foreach ($usermeta_data_raw as $usermeta_row) {
		foreach ($usermeta_row as $key => $value) {
			if($key == "meta_key") {
				$meta_key = $value;
			} elseif($key == "meta_value") {
				$meta_value = $value;
			}
		}
		$usermeta_data_table += [$meta_key => $meta_value];
	}

	return $usermeta_data_table ;

} // get_usermeta_by_ID()


/**************************************************************************************************
 *								FUNCTION get_userID_By_username()								  *
 **************************************************************************************************/

function get_userID_By_username($username) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "SELECT ID FROM `".$users."` WHERE `user_login`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	$user_id_raw = iWhileFetch($users_sql);
	// print_r($user_id_raw);
	return $user_id = $user_id_raw[0]['ID'];

} // get_userID_By_username()


/**************************************************************************************************
 *								FUNCTION get_userID_and_username()								  *
 **************************************************************************************************/

function get_userID_and_username() {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "SELECT ID, display_name FROM `" . $users . "`";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	return $users_array = iWhileFetch($users_sql);
} // get_userID_and_username()




/**************************************************************************************************
 *								FUNCTION get_user_display_name_By_Id()							  *
 **************************************************************************************************/

function get_user_display_name_By_Id($user_id) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql = "SELECT display_name FROM `".$users."` WHERE `ID`=$user_id";

	return $user_registered_date = iWhileFetch($users_sql);
} // get_user_display_name_By_Id()


/**************************************************************************************************
 *								FUNCTION get_user_registered_By_Id()							  *
 **************************************************************************************************/

function get_user_registered_By_Id($user_id) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql = "SELECT user_registered FROM `".$users."` WHERE `ID`=$user_id";

	return $user_registered_date = iWhileFetch($users_sql);
} // get_user_registered_By_Id()


/**************************************************************************************************
 *							FUNCTION get_etablissement_color_By_Id()							  *
 **************************************************************************************************/

function get_etablissement_color_By_Id($user_id) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql = "SELECT etablissement_color FROM `".$users."` WHERE `ID`=$user_id";

	return $etablissement_color = iWhileFetch($users_sql);
} // get_etablissement_color_By_Id()


/**************************************************************************************************
 *							FUNCTION get_etablissement_color_By_username()						  *
 **************************************************************************************************/

function get_etablissement_color_By_username($username) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "SELECT etablissement_color FROM `".$users."` WHERE `user_login`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	return $etablissement_color = iWhileFetch($users_sql);
} // get_etablissement_color_By_username()




/**************************************************************************************************
 *								FUNCTION get_all_user_docs_By_Id(									  *
 **************************************************************************************************/

/**
 * Get the banner of the client connected using his ID
 *
 * @param [type] $user_id
 * @return void
 */
function get_all_user_docs_By_Id($user_id) {
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';

	$cn_user_doce_sql = "SELECT * FROM `" . $cn_user_doce . "` WHERE `user_id`=$user_id ORDER BY `ordering` ASC";

	return $cn_user_doce_details = iWhileFetch($cn_user_doce_sql);
} // get_all_user_docs_By_Id()



/**************************************************************************************************
 *								FUNCTION get_user_banner_By_Id(									  *
 **************************************************************************************************/

/**
 * Get the banner of the client connected using his ID
 *
 * @param [type] $user_id
 * @return void
 */
function get_user_banner_By_Id($user_id) {
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';

	$cn_user_doce_sql = "SELECT * FROM `" . $cn_user_doce . "` WHERE `user_id`=$user_id AND `banner`='yes' ORDER BY `ordering` ASC";

	return $cn_user_doce_details = iWhileFetch($cn_user_doce_sql);
} // get_user_banner_By_Id()


/**************************************************************************************************
*									FUNCTION get_user_documents_By_Id()							  *
**************************************************************************************************/

/**
 * Récupère les infos des documents dans la table cn_user_doce en fonction de l'id du client 
 * et si ce n'est pas une bannière
 *
 * Used in: upload_document()	/	cn_public_page()	/	user_banner()
 * @param [type] $user_id
 * @return void
 */
function get_user_documents_By_Id($user_id) {
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
	$cn_user_doce_sql = "SELECT * FROM `".$cn_user_doce."` WHERE `user_id`=$user_id AND `banner`!='yes' ORDER BY `ordering` ASC";
	
	return $cn_user_doce_details = iWhileFetch($cn_user_doce_sql);
} // get_user_documents_By_Id()


/**************************************************************************************************
 *									FUNCTION get_docs_path_By_docs_id()								  *
 **************************************************************************************************/

/**
 * Récupère, dans la table cn_user_doce, le chemin des banières et des documents en fonction de leur id
 *
 * Used in: upload_document()	/	cn_public_page()	/	user_banner()
 * @param [type] $user_id
 * @return void
 */
function get_docs_path_By_docs_id($item_id) {
	global $wpdb;
	$cn_user_doce_table = $wpdb->prefix . 'cn_user_doce';
	$get_file_path_sql = "select file from `".$cn_user_doce_table."` WHERE `id`=$item_id";
	
	return $docs_path_By_docs_id = iWhileFetch($get_file_path_sql);
} // get_docs_path_By_docs_id()



/**************************************************************************************************
 *								FUNCTION insert_user_doc_By_Id()								  *
 **************************************************************************************************/

function insert_user_doc_By_Id($uploaded_file, $file_data) {
	global $wpdb;
	$table_cn_user_doce = $wpdb->prefix . 'cn_user_doce';
	get_current_user_id();
	
	$ordering = $file_data['ordering'];
	$banner = $file_data['banner'];
	$link = $file_data['link'];
	$cn_to = $file_data['cn_to'];
	$cn_from = $file_data['cn_from'];

	foreach ($uploaded_file as $key => $value) {

		$result_data = array(
			'user_id' => get_current_user_id(),
			'file' => $value,
			'ordering' => $ordering,
			'banner' => $banner,
			'link' => $link,
			'cn_from' => $cn_from,
			'cn_to' => $cn_to,
		);

		$response = iInsert($table_cn_user_doce,$result_data);
		$response = json_decode($response);
		$response;
		$ordering++; // incrémente de 1 à chaque image pour fixer l'ordre
	}

	return $response;
} // insert_user_doc_By_Id()

