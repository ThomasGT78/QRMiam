<?php

// NOT USED

// iQuery()
// iMainQuery()
// iWhileFetch()
// iInsert()
// iUpdateArray()
// iUpdateArrayInt()
// iUpdateArrayMultiConds()
// get_etablissement_color_By_Id()
// get_etablissement_color_By_username()
// get_nbre_vue_By_Id()
// get_stat_nbre_vue_By_user_id()
// get_stat_nbre_vue_By_user_id_today()
// get_stat_nbre_vue_By_user_id_And_Date()
// get_stat_nbre_vue_By_user_id_And_Date_hourly()
// get_stat_nbre_vue_By_user_id_now()
// get_userID_By_username()
// get_userID_and_username()
// get_user_banner_By_Id()
// get_user_documents_By_Id()
// insert_user_doc_By_Id()
// insert_stat_nbre_vue()

/**************************************************************************************************
 *										FUNCTION iQuery()										  *
 **************************************************************************************************/

function iQuery($SQL, &$rs) {
	if(iMainQuery($SQL,$rs)) {
		return true;
	}
	else {
		return false;
	}
} // iQuery()


/**************************************************************************************************
 *										FUNCTION iMainQuery()									  *
 **************************************************************************************************/

function iMainQuery($SQL, &$rs) {
	global $wpdb;
	// requête sur la base de données
	$rs = $wpdb->query($SQL);
	if($wpdb->last_error) { // si il y a eu une erreur
		$error = $wpdb->last_error;
		$last_query = $wpdb->last_query;
		$success = 'warning';
	}
	else{
		$success = 'success';
	}

	$response = array('success' => $success, 'error' => $error, 'last_query' => $last_query);
	return $response;
} // iMainQuery()


/**************************************************************************************************
 *									FUNCTION iWhileFetch()										  *
 **************************************************************************************************/

/**
 * Requête générale sur la BD et transformation du résultat au format json
 *
 * @param [type] $sql
 * @return json type
 */ 
function iWhileFetch($sql) {
	global $wpdb;
	$record = $wpdb->get_results($sql); // return array object
	$record = json_decode(json_encode($record),true);
	return $record;
} // iWhileFetch()


/**************************************************************************************************
 *										FUNCTION iInsert()										  *
 **************************************************************************************************/

/**
 * Undocumented function
 *
 * @param [type] $table
 * @param array $postData
 * @param string $html_spl
 * @return json_encode($response)
 */
function iInsert($table, $postData = array(),$html_spl='No') {
	global $wpdb;
	$sql = "DESC $table";
	$getFields = array();		
	$fieldArr = $wpdb->get_results($sql);
	foreach($fieldArr as $field)
	{
		$field=json_decode(json_encode($field),true);
		$getFields[sizeof($getFields)] = $field['Field'];
	}
	$fields = "";
	$values = "";
	if (sizeof($getFields) > 0) {				
		foreach($getFields as $k) {
			if (isset($postData[$k])) {
				if($html_spl == 'No') {
					$postData[$k] = $postData[$k];
				} 
				else {
					$postData[$k] = htmlspecialchars($postData[$k]);
				}

				$fields .= "`$k`, ";
				$values .= "'$postData[$k]', ";
			}
		}

		$fields = substr($fields, 0, strlen($fields) - 2);
		$values = substr($values, 0, strlen($values) - 2);

		$insert = "INSERT INTO $table ($fields) VALUES ($values)";

		$rs = $wpdb->query($insert);
		if($wpdb->last_error){
			$error = $wpdb->last_error;
			$last_query = $wpdb->last_query;
			$success = 'warning';
			$msg = 'Something was wrong';
		}
		else{
			$success = 'success';
			$msg='Added successfully';
			$insert_id=$wpdb->insert_id;
		}
	}
	else {
		$msg = 'Something was wrong';
		$success = 'warning';
	}

	$response = array(
		'success' => $success,
		'msg' => $msg,
		'error' => $error,
		'last_query' => $last_query,
		'insert_id' => $insert_id);

	return json_encode($response);
} // function iInsert()


/**************************************************************************************************
 *									FUNCTION iUpdateArray()										  *
 **************************************************************************************************/

function iUpdateArray($table, $postData = array(), $conditions = array(), $html_spl='No') {

	global $wpdb;

	foreach($postData as $k=>$value) {				
		if($html_spl == 'Yes') {
			$value = htmlspecialchars($value);
		}

		if($value == NULL){
			$values .= "`$k` = NULL, ";
		} else {
			$values .= "`$k` = '$postData[$k]', ";
		}
	}

	$values = substr($values, 0, strlen($values) - 2);

	foreach($conditions as $k => $v) {
		$v = htmlspecialchars($v);			
		$conds .= "$k = '$v'";
	}
	
	$update = "UPDATE `$table` SET $values WHERE $conds";
	$rs = $wpdb->query($update);

	if($wpdb->last_error) {
		$error=$wpdb->last_error;
		$last_query=$wpdb->last_query;
		$success='warning';
		$msg='Something was wrong';
	}
	else {
		$success='success';
		$msg='updated successfully';
	}

	$response = array(
		'success' => $success,
		'msg' => $msg,
		'error' => $error,
		'last_query' => $last_query
	);

	return json_encode($response);
} // iUpdateArray()


/**************************************************************************************************
 *									FUNCTION iUpdateArrayInt()									  *
 **************************************************************************************************/

function iUpdateArrayInt($table, $postData = array(), $conditions = array(), $html_spl='No') {
	global $wpdb;
	$values = '';

	foreach($postData as $k=>$value) {				
		if($html_spl == 'Yes') {
			$value = htmlspecialchars($value);
		}

		if($value == NULL){
			$values .= "`$k` = NULL, ";
		} else {
			$values .= "`$k` = $postData[$k], ";
		}
	}

	$values = substr($values, 0, strlen($values) - 2);
	$conds = '';

		foreach($conditions as $k => $v) {
			$v = htmlspecialchars($v);
			$conds .= "`$k` = $v";
		}
	
	// Requête SQL
	$update = "UPDATE `$table` SET $values WHERE $conds";
	$rs = $wpdb->query($update);

	if($wpdb->last_error) {
		$error=$wpdb->last_error;
		$last_query=$wpdb->last_query;
		$success='warning';
		$msg='Something was wrong';
	}
	else {
		$success='success';
		$msg='updated successfully';
	}

	$response = array(
		'success' => $success,
		'msg' => $msg,
		'error' => $error,
		'last_query' => $last_query
	);

	return json_encode($response);
} // iUpdateArrayInt()



/**************************************************************************************************
 *								FUNCTION iUpdateArrayMultiConds()								  *
 **************************************************************************************************/

function iUpdateArrayMultiConds($table, $postData = array(), $conditions = array(), $html_spl='No') {
	global $wpdb;
	$valuesToSet = '';

	// VALUES TO SET
	foreach($postData as $key=>$value) {				
		if($html_spl == 'Yes') {
			$value = htmlspecialchars($value);
		}

		if($value == NULL && $value !== 0){
			$valuesToSet .= "`$key` = NULL, ";
		} else {
			if (is_numeric($value)){
				$valuesToSet .= "`$key` = $value, ";
			} else {
				$valuesToSet .= "`$key` = '$value', ";
			}
		}
	}

	// Pour enlever la , et l'espace à la fin de "`$k` = NULL, "
	$valuesToSet = substr($valuesToSet, 0, strlen($valuesToSet) - 2);

	// CONDITIONS
	$conds = '';
	if(count($conditions) == 1) {
		foreach($conditions as $k => $v) {
			$v = htmlspecialchars($v);
			if (is_numeric($v) || strtotime($v)){
			// if (is_int($v) || date_parse($v)){
				$conds .= "`$k` = $v";
			} else {
				$conds .= "`$k` = '$v'";
			}
		}
	} else {
		$i = 0;
		foreach($conditions as $k => $v) {
			$v = htmlspecialchars($v);
			if ($i == 0) {
				if (is_numeric($v) || strtotime($v)){
					$conds .= "`$k` = $v";
				} elseif (is_string($v)){
					$conds .= "`$k` = '$v'";
				}
			} else {
				if (is_numeric($v) || strtotime($v)){
					$conds .= " AND `$k` = $v";
				} elseif (is_string($v)) {
					$conds .= " AND `$k` = '$v'";
				}
			}
			$i++;
		}
	}
	
	// Requête SQL
	$update = "UPDATE `$table` SET $valuesToSet WHERE $conds";
	$rs = $wpdb->query($update);

	if($wpdb->last_error) {
		$error=$wpdb->last_error;
		$last_query=$wpdb->last_query;
		$success='warning';
		$msg='Something was wrong';
	}
	else {
		$success='success';
		$msg='updated successfully';
	}

	$response = array(
		'success' => $success,
		'msg' => $msg,
		'error' => $error,
		'last_query' => $last_query
	);

	return json_encode($response);
} // iUpdateArrayMultiConds()

/**************************************************************************************************
 *							FUNCTION get_etablissement_color_By_Id()							  *
 **************************************************************************************************/

function get_etablissement_color_By_Id($user_id) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql = "select etablissement_color from `".$users."` WHERE `ID`=$user_id";

	return $etablissement_color = iWhileFetch($users_sql);
} // get_etablissement_color_By_Id()


/**************************************************************************************************
 *							FUNCTION get_etablissement_color_By_username()						  *
 **************************************************************************************************/

function get_etablissement_color_By_username($username) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "select etablissement_color from `".$users."` WHERE `display_name`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	return $etablissement_color = iWhileFetch($users_sql);
} // get_etablissement_color_By_username()


/**************************************************************************************************
 *								FUNCTION get_nbre_vue_By_Id()									  *
 **************************************************************************************************/

function get_nbre_vue_By_Id($user_id) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql = "select nbre_vue from `".$users."` WHERE `ID`=$user_id";

	return $nbre_vue = iWhileFetch($users_sql);
} // get_nbre_vue_By_Id()


/**************************************************************************************************
 *								FUNCTION get_stat_nbre_vue_By_user_id()							  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id($user_id) {
	global $wpdb;
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$nbre_vue_per_id_sql = "select SUM(nbre_vue) AS 'somme' from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\"";

	return $nbre_vue_per_id = iWhileFetch($nbre_vue_per_id_sql);
} // get_stat_nbre_vue_By_user_id()


/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_By_user_id_today()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_today($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date=date("Y-m-d");
	$nbre_vue_per_id_and_date_sql="select SUM(nbre_vue) AS 'somme' from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\"";
	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_per_id_and_date_sql);
} // get_stat_nbre_vue_By_user_id_today()


/**************************************************************************************************
 *						FUNCTION get_stat_nbre_vue_By_user_id_And_Date()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_And_Date($user_id,$year,$month,$day,$hour){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$todays_date_year=date("Y");
	$todays_date_month=date("m");
	$todays_date_day=date("d");
	$todays_date_hour=date("H");
	$requested_date=$year."-".$month."-".$day;
	$requested_hour=$hour;
	//echo $requested_hour;
	$nbre_vue_per_id_date_hour_sql="select SUM(nbre_vue) AS 'somme' from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$requested_date\" AND `heure`=$requested_hour";
	//echo $nbre_vue_per_id_date_hour_sql;

	return $nbre_vue_per_id_date_hour = iWhileFetch($nbre_vue_per_id_date_hour_sql);
} // get_stat_nbre_vue_By_user_id_And_Date()


/**************************************************************************************************
 *						FUNCTION get_stat_nbre_vue_By_user_id_And_Date_hourly()					  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_And_Date_hourly($user_id,$year,$month,$day) {
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$requested_date = $year."-".$month."-".$day;

	$nbre_vue_per_id_and_date_sql = "select nbre_vue, date, heure from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$requested_date\" ORDER BY heure";
	//echo $nbre_vue_per_id_and_date_sql;

	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_per_id_and_date_sql);
} // get_stat_nbre_vue_By_user_id_And_Date_hourly()


/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_By_user_id_now()							  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_now($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date=date("Y-m-d");
	$heure=date("H");

	$nbre_vue_per_id_date_now_sql = "select * from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\" AND `heure`=\"$heure\"";
	
	return $nbre_vue_per_id_date_now = iWhileFetch($nbre_vue_per_id_date_now_sql);
} // get_stat_nbre_vue_By_user_id_now()


/**************************************************************************************************
 *								FUNCTION get_userID_By_username()								  *
 **************************************************************************************************/

function get_userID_By_username($username) {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "select ID from `".$users."` WHERE `display_name`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	return $user_id = iWhileFetch($users_sql);
} // get_userID_By_username()


/**************************************************************************************************
 *								FUNCTION get_userID_and_username()								  *
 **************************************************************************************************/

function get_userID_and_username() {
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";

	$users_sql = "select ID, display_name from `" . $users . "`";
	//print_r($etablissement_color=iWhileFetch($users_sql));

	return $users_array = iWhileFetch($users_sql);
} // get_userID_and_username()


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

	$cn_user_doce_sql = "select * from `" . $cn_user_doce . "` WHERE `user_id`=$user_id AND `banner`='yes' ORDER BY `ordering` ASC";

	return $cn_user_doce_details = iWhileFetch($cn_user_doce_sql);
} // get_user_banner_By_Id()


/**************************************************************************************************
 *									FUNCTION get_user_documents_By_Id()								  *
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
	$cn_user_doce_sql = "select * from `".$cn_user_doce."` WHERE `user_id`=$user_id AND `banner`!='yes' ORDER BY `ordering` ASC";
	
	return $cn_user_doce_details = iWhileFetch($cn_user_doce_sql);
} // get_user_documents_By_Id()

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
		$ordering += $key;	// incrémente de 1 à chaque image

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
	}

	return $response;
} // insert_user_doc_By_Id()


/**************************************************************************************************
 *								FUNCTION insert_stat_nbre_vue()									  *
 **************************************************************************************************/

function insert_stat_nbre_vue() {
	global $wpdb;
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	if($_GET['username']) {
		$user_id = get_userID_By_username($_GET['username']);
		$user_id = $user_id[0]['ID'];

		if ($user_id!=0) {
			$result_data=array('user_id'=>$user_id,'date'=>date("Y-m-d"),'heure'=>date("H"),'nbre_vue'=>1);
			$response=iInsert($table_stat_nbre_vue,$result_data);
			$response=json_decode($response);
			//print_r($response);
		}
	}	
} // insert_stat_nbre_vue()
