<?php

// iQuery()
// iMainQuery()
// iWhileFetch()
// iInsert()
// iUpdateArray()
// iUpdateArrayInt()
// iUpdateArrayMultiConds()


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
 *										FUNCTION generalQuery()									  *
 **************************************************************************************************/

function generalQuery($SQL) {
	global $wpdb;
	// requête sur la base de données
	$result = $wpdb->query($SQL);
	if($wpdb->last_error) { // s'il y a eu une erreur
		$error = $wpdb->last_error;
		$last_query = $wpdb->last_query;
		$success = 'warning';
	}
	else{
		$success = 'success';
	}

	$response = array('success' => $success, 'error' => $error, 'last_query' => $last_query);
	
	return $response;

} // generalQuery()


/**************************************************************************************************
 *									FUNCTION iWhileFetch()										  *
 **************************************************************************************************/

/**
 * Requête générale sur la BD et conversion du résultat json en objet PHP
 *
 * @param [type] $sql
 * @return array type
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
		$field = json_decode(json_encode($field),true);
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
	$values = '';
	$conds = '';

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

function iUpdateArrayMultiConds($table, $postData = array(), $conditions = array()) {
	global $wpdb;
	$valuesToSet = '';

	// VALUES TO SET
	foreach($postData as $key=>$value) {
		$value = htmlspecialchars($value);

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

	// Pour enlever la «, » à la fin de «`$k` = NULL, »
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
				if (is_numeric($v)){
					$conds .= "`$k` = $v";
				} elseif (is_string($v)){
					$conds .= "`$k` = '$v'";
				}
			} else {
				if (is_numeric($v)){
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

