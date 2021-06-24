<?php

// insert_stat_nbre_vue()
// get_nbre_vue_By_Id()
// get_stat_nbre_vue_By_user_id()
// get_stat_nbre_vue_By_user_id_today()
// get_stat_nbre_vue_By_user_id_And_Date()
// get_stat_nbre_vue_By_user_id_And_Date_hourly()
// get_stat_nbre_vue_By_user_id_now()


/**************************************************************************************************
 *								FUNCTION insert_stat_nbre_vue()									  *
 **************************************************************************************************/

function insert_stat_nbre_vue() {
	global $wpdb;
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	if($_GET['username']) {
		$user_id = get_userID_By_username($_GET['username']);

		if ($user_id != 0) {
			$result_data = array('user_id'=>$user_id,'date'=>date("Y-m-d"),'heure'=>date("H"),'nbre_vue'=>1);
			$response = iInsert($table_stat_nbre_vue,$result_data);
			$response = json_decode($response);
			//print_r($response);
		}
	}	
} // insert_stat_nbre_vue()


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
	$nbre_vue_per_id_sql = "SELECT SUM(nbre_vue) AS 'somme' FROM `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\"";

	return $nbre_vue_per_id = iWhileFetch($nbre_vue_per_id_sql);
} // get_stat_nbre_vue_By_user_id()



/**************************************************************************************************
 *								FUNCTION get_stat_nbre_vue_all_users()							  *
 **************************************************************************************************/
// NOT USED YET
function get_stat_nbre_vue_all_users() {
	global $wpdb;
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$nbre_vue_all_users_sql = "SELECT SUM(nbre_vue) AS 'somme' FROM `".$table_stat_nbre_vue."`";

	return $nbre_vue_all_users = iWhileFetch($nbre_vue_all_users_sql);
} // get_stat_nbre_vue_all_users()


/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_By_user_id_today()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_today($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	 
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date = date("Y-m-d");

	$nbre_vue_per_id_and_date_sql = "select SUM(nbre_vue) AS 'somme' from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\"";

	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_per_id_and_date_sql);

} // get_stat_nbre_vue_By_user_id_today()


/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_all_users_today()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_all_users_today(){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	 
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date = date("Y-m-d");

	$nbre_vue_all_users_per_date_sql = "SELECT SUM(nbre_vue) AS 'somme' FROM `".$table_stat_nbre_vue."` WHERE `date`=\"$date\"";

	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_all_users_per_date_sql);

} // get_stat_nbre_vue_all_users_today()



/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_all_users_this_hour()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_all_users_this_hour(){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	 
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date = date("Y-m-d");
	$hour = date("H");

	$nbre_vue_all_users_per_date_sql = "SELECT SUM(nbre_vue) AS 'somme' FROM `".$table_stat_nbre_vue."` WHERE `date`=\"$date\" AND `heure`=\"$hour\"";

	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_all_users_per_date_sql);

} // get_stat_nbre_vue_all_users_this_hour()


/**************************************************************************************************
 *							FUNCTION get_stat_nbre_vue_By_user_id_now()							  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_now($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	$date = date("Y-m-d");
	$heure = date("H");

	$nbre_vue_per_id_date_now_sql = "select * from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\" AND `heure`=\"$heure\"";
	
	return $nbre_vue_per_id_date_now = iWhileFetch($nbre_vue_per_id_date_now_sql);
} // get_stat_nbre_vue_By_user_id_now()


/**************************************************************************************************
 *						FUNCTION get_stat_nbre_vue_By_user_id_And_Date()						  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_And_Date($user_id, $year, $month, $day, $hour){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	$todays_date_year = date("Y");
	$todays_date_month = date("m");
	$todays_date_day = date("d");
	$todays_date_hour = date("H");
	$requested_date = $year."-".$month."-".$day;
	$requested_hour = $hour;
	//echo $requested_hour;
	$nbre_vue_per_id_date_hour_sql="select SUM(nbre_vue) AS 'somme' from `".$table_stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$requested_date\" AND `heure`=$requested_hour";
	//echo $nbre_vue_per_id_date_hour_sql;

	return $nbre_vue_per_id_date_hour = iWhileFetch($nbre_vue_per_id_date_hour_sql);

} // get_stat_nbre_vue_By_user_id_And_Date()


/**************************************************************************************************
 *						FUNCTION get_stat_nbre_vue_By_user_id_And_Date_hourly()					  *
 **************************************************************************************************/

function get_stat_nbre_vue_By_user_id_And_Date_hourly($user_id, $start_date, $end_date) {
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	if(!$end_date){ // Specific Date
		$nbre_vue_per_id_and_date_sql = 
		"SELECT nbre_vue, date, heure 
			FROM `".$table_stat_nbre_vue."` 
			WHERE `user_id`=\"$user_id\" 
			AND `date`=\"$start_date\" 
			ORDER BY heure";
	} else { // Period
		
		$nbre_vue_per_id_and_date_sql = 
		"SELECT SUM(nbre_vue) AS nbre_vue, date 
			FROM `$table_stat_nbre_vue` 
			WHERE `user_id`=\"$user_id\" 
				AND `date` BETWEEN \"$start_date\" 
				AND \"$end_date\" 
			GROUP BY date 
			ORDER BY date";
	}
	//echo $nbre_vue_per_id_and_date_sql;

	return $nbre_vue_per_id_and_date = iWhileFetch($nbre_vue_per_id_and_date_sql);

} // get_stat_nbre_vue_By_user_id_And_Date_hourly()


/**************************************************************************************************
 *						FUNCTION get_stat_nbre_vue_all_users_per_date_hourly()					  *
 **************************************************************************************************/

function get_stat_nbre_vue_all_users_per_date_hourly($start_date, $end_date) {
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	if(!$end_date){ // Specific Date
		$nbre_vue_all_users_per_date_sql = 
		"SELECT nbre_vue, date, heure 
			FROM `".$table_stat_nbre_vue."` 
			WHERE `date`=\"$start_date\" 
			ORDER BY heure";
	} 
	else { // Period
		$nbre_vue_all_users_per_date_sql = 
		"SELECT SUM(nbre_vue) AS nbre_vue, date 
			FROM `$table_stat_nbre_vue` 
			WHERE `date` BETWEEN \"$start_date\" 
				AND \"$end_date\" 
			GROUP BY date 
			ORDER BY date";
	}
	//echo $nbre_vue_per_id_and_date_sql;

	return $nbre_vue_all_users_per_date = iWhileFetch($nbre_vue_all_users_per_date_sql);

} // get_stat_nbre_vue_all_users_per_date_hourly()


/**************************************************************************************************
 *						FUNCTION get_average_vues_By_user_id_And_Date_hourly()					  *
 **************************************************************************************************/

function get_average_vues_By_user_id_And_Date_hourly($user_id, $start_date, $end_date) {
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	$average_vue_per_hour_sql = 
		"SELECT AVG(nbre_vue) as avgVues, heure 
			FROM `$table_stat_nbre_vue` 
			WHERE `user_id`=\"$user_id\" 
			AND `date` BETWEEN \"$start_date\" AND \"$end_date\"  
			GROUP BY heure 
			ORDER BY heure
		;";
	//echo $nbre_vue_per_id_and_date_sql;

	return $average_vue_per_hour = iWhileFetch($average_vue_per_hour_sql);

} // get_average_vues_By_user_id_And_Date_hourly()


/**************************************************************************************************
 *						FUNCTION get_average_vues_all_users_per_date_hourly()					  *
 **************************************************************************************************/

function get_average_vues_all_users_per_date_hourly($start_date, $end_date) {
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$table_stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';

	$average_vue_per_hour_sql = 
		"SELECT AVG(nbre_vue) as avgVues, heure 
			FROM `$table_stat_nbre_vue` 
			WHERE `date` BETWEEN \"$start_date\" AND \"$end_date\"  
			GROUP BY heure 
			ORDER BY heure
		;";
	//echo $nbre_vue_per_id_and_date_sql;

	return $average_vue_per_hour = iWhileFetch($average_vue_per_hour_sql);

} // get_average_vues_all_users_per_date_hourly()