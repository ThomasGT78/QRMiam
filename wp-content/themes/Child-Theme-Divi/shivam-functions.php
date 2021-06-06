<?php

/**
 * @Author: Sharma
 * @Date:   2020-06-02 18:30:20
 * @Last Modified by:   Sharma
 * @Last Modified time: 2020-06-25 13:34:40
 */


 global $wpdb;
 date_default_timezone_set('Europe/Paris');
// $charset_collate = $wpdb->get_charset_collate();
// $cn_user_doce = $wpdb->prefix . 'cn_user_doce';
// 		$sql = "CREATE TABLE $cn_user_doce (
// 				`id` int(11) NOT NULL AUTO_INCREMENT,
// 				`user_id` varchar(50) DEFAULT NULL,
// 				`file` varchar(150) DEFAULT NULL,
// 				`ordering` varchar(15) DEFAULT NULL,
// 				`created` datetime DEFAULT NULL,
// 				`modified` datetime DEFAULT NULL,
// 			PRIMARY KEY  (id)
// 		) $charset_collate;";
// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
// dbDelta($sql);
 function iQuery($SQL,&$rs)
	{
		if(iMainQuery($SQL,$rs))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function iMainQuery($SQL,&$rs)
	{
		global $wpdb;		
		$rs=$wpdb->query($SQL);
		if($wpdb->last_error){
			$error=$wpdb->last_error;
			$last_query=$wpdb->last_query;
			$success='warning';
		}
		else{
			$success='success';
		}
		$response=array('success'=> $success,'error'=> $error,'last_query' =>$last_query);
		return $response;
	}
function iWhileFetch($sql){
		global $wpdb;
		$record = $wpdb->get_results($sql);
		$record=json_decode(json_encode($record),true);
		return $record;
}

function iInsert($table, $postData = array(),$html_spl='No'){
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
		if (sizeof($getFields) > 0)
		{				
			foreach($getFields as $k)
			{
				if (isset($postData[$k]))
				{
					if($html_spl=='No')
					{
						$postData[$k] = $postData[$k];
					}
					else
					{
						$postData[$k] = htmlspecialchars($postData[$k]);
					}
					$fields .= "`$k`, ";
					$values .= "'$postData[$k]', ";
				}
			}			
			$fields = substr($fields, 0, strlen($fields) - 2);
			$values = substr($values, 0, strlen($values) - 2);
			$insert = "INSERT INTO $table ($fields) VALUES ($values)";
			$rs=$wpdb->query($insert);
			if($wpdb->last_error){
				$error=$wpdb->last_error;
				$last_query=$wpdb->last_query;
				$success='warning';
				$msg='Something was wrong';
			}
			else{
				$success='success';
				$msg='Added successfully';
				$insert_id=$wpdb->insert_id;
			}
		}
		else
		{
			$msg='Something was wrong';
			$success='warning';
		}
		$response=array('success'=> $success,'msg'=>$msg,'error'=> $error,'last_query' =>$last_query,'insert_id'=>$insert_id);
		return json_encode($response);
}	

function iUpdateArray($table, $postData = array(),$conditions = array(),$html_spl='No')
	{
		global $wpdb;
		foreach($postData as $k=>$value)
		{				
			if($html_spl=='Yes')
			{
				$value = htmlspecialchars($value);
			}
			if($value==NULL)
				$values .= "`$k` = NULL, ";
			else
				$values .= "`$k` = '$postData[$k]', ";
		}
		$values = substr($values, 0, strlen($values) - 2);
		foreach($conditions as $k => $v)
		{
			$v = htmlspecialchars($v);			
			$conds .= "$k = '$v'";
		}			
		$update = "UPDATE `$table` SET $values WHERE $conds";
		$rs=$wpdb->query($update);
		if($wpdb->last_error){
			$error=$wpdb->last_error;
			$last_query=$wpdb->last_query;
			$success='warning';
			$msg='Something was wrong';
		}
		else{
			$success='success';
			$msg='updated successfully';
		}
		$response=array('success'=> $success,'msg'=>$msg,'error'=> $error,'last_query' =>$last_query);
		return json_encode($response);
	}
function iUpdateArrayInt($table, $postData = array(),$conditions = array(),$html_spl='No')
	{
		global $wpdb;
		foreach($postData as $k=>$value)
		{				
			if($html_spl=='Yes')
			{
				$value = htmlspecialchars($value);
			}
			if($value==NULL)
				$values .= "`$k` = NULL, ";
			else
				$values .= "`$k` = $postData[$k], ";
		}
		$values = substr($values, 0, strlen($values) - 2);
		foreach($conditions as $k => $v)
		{
			$v = htmlspecialchars($v);			
			$conds .= "`$k` = $v";
		}			
		$update = "UPDATE `$table` SET $values WHERE $conds";
		$rs=$wpdb->query($update);
		if($wpdb->last_error){
			$error=$wpdb->last_error;
			$last_query=$wpdb->last_query;
			$success='warning';
			$msg='Something was wrong';
		}
		else{
			$success='success';
			$msg='updated successfully';
		}
		$response=array('success'=> $success,'msg'=>$msg,'error'=> $error,'last_query' =>$last_query);
		return json_encode($response);
	}

function get_user_doc_By_Id($user_id){
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
	$cn_user_doce_sql="select * from `".$cn_user_doce."` WHERE `user_id`=$user_id AND `banner`!='yes' ORDER BY `ordering` ASC";
	return $cn_user_doce_details=iWhileFetch($cn_user_doce_sql);
}

function get_etablissement_color_By_Id($user_id){
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql="select etablissement_color from `".$users."` WHERE `ID`=$user_id";
	return $etablissement_color=iWhileFetch($users_sql);

}

function get_nbre_vue_By_Id($user_id){
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	$users_sql="select nbre_vue from `".$users."` WHERE `ID`=$user_id";
	return $nbre_vue=iWhileFetch($users_sql);

}

function get_stat_nbre_vue_By_user_id($user_id){
	global $wpdb;
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$stat_nbre_vue_sql="select SUM(nbre_vue) AS 'somme' from `".$stat_nbre_vue."` WHERE `user_id`=\"$user_id\"";
	return $stat_nbre_vue=iWhileFetch($stat_nbre_vue_sql);

}
function get_stat_nbre_vue_By_user_id_today($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date=date("Y-m-d");
	$stat_nbre_vue_sql="select SUM(nbre_vue) AS 'somme' from `".$stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\"";
	return $stat_nbre_vue=iWhileFetch($stat_nbre_vue_sql);

}

function get_stat_nbre_vue_By_user_id_And_Date($user_id,$year,$month,$day,$hour){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$todays_date_year=date("Y");
	$todays_date_month=date("m");
	$todays_date_day=date("d");
	$todays_date_hour=date("H");
	$requested_date=$year."-".$month."-".$day;
	$requested_hour=$hour;
	//echo $requested_hour;
	$stat_nbre_vue_sql="select SUM(nbre_vue) AS 'somme' from `".$stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$requested_date\" AND `heure`=$requested_hour";
	//echo $stat_nbre_vue_sql;

	return $stat_nbre_vue=iWhileFetch($stat_nbre_vue_sql);

}

function get_stat_nbre_vue_By_user_id_And_Date_hourly($user_id,$year,$month,$day){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$requested_date=$year."-".$month."-".$day;

	$stat_nbre_vue_sql="select nbre_vue, date, heure from `".$stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$requested_date\" ORDER BY heure";
	//echo $stat_nbre_vue_sql;

	return $stat_nbre_vue=iWhileFetch($stat_nbre_vue_sql);

}

function get_stat_nbre_vue_By_user_id_now($user_id){
	global $wpdb;
	date_default_timezone_set('Europe/Paris');
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	$date=date("Y-m-d");
	$heure=date("H");
	$stat_nbre_vue_sql="select * from `".$stat_nbre_vue."` WHERE `user_id`=\"$user_id\" AND `date`=\"$date\" AND `heure`=\"$heure\"";
	return $stat_nbre_vue=iWhileFetch($stat_nbre_vue_sql);

}


function get_etablissement_color_By_username($username){
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";
	$users_sql="select etablissement_color from `".$users."` WHERE `display_name`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));
	return $etablissement_color=iWhileFetch($users_sql);


	}

function get_userID_By_username($username){
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";
	$users_sql="select ID from `".$users."` WHERE `display_name`=\"$username\"";
	//print_r($etablissement_color=iWhileFetch($users_sql));
	return $user_id=iWhileFetch($users_sql);


	}

	function get_userID_and_username(){
	global $wpdb;
	$users = $wpdb->prefix . 'users';
	//$username="victor_test";
	$users_sql="select ID, display_name from `".$users."`";
	//print_r($etablissement_color=iWhileFetch($users_sql));
	return $users_array=iWhileFetch($users_sql);


	}


function get_user_banner_By_Id($user_id){
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
	$cn_user_doce_sql="select * from `".$cn_user_doce."` WHERE `user_id`=$user_id AND `banner`='yes' ORDER BY `ordering` ASC";
	return $cn_user_doce_details=iWhileFetch($cn_user_doce_sql);
}


function insert_user_doc_By_Id($cn_data,$user_doc_date){
	global $wpdb;
	$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
	get_current_user_id();
	$cn_to=$user_doc_date['cn_to'];
	$cn_from=$user_doc_date['cn_from'];
	$banner=$user_doc_date['banner'];
	$link=$user_doc_date['link'];
	foreach ($cn_data as $key => $value) {
		$result_data=array('user_id'=>get_current_user_id(),'file'=>$value,'banner'=>$banner,'link'=>$link,'cn_from'=>$cn_from,'cn_to'=>$cn_to,'ordering'=>$key);
		$response=iInsert($cn_user_doce,$result_data);
		$response=json_decode($response);
		$response;
	}
	// $cn_from=$cn_data['cn_from'];
	// $cn_to=$cn_data['cn_to'];
	// $result_data=array('user_id'=>get_current_user_id(),'file'=>$cn_data['user_doc_url'],);
	// $response=iInsert($cn_user_doce,$result_data);
	// $response=json_decode($response);
	return $response;
}

function insert_stat_nbre_vue(){
	global $wpdb;
	$stat_nbre_vue = $wpdb->prefix . 'stat_nbre_vue';
	if($_GET['username']) {
		$user_id=get_userID_By_username($_GET['username']);
		$user_id=$user_id[0]['ID'];
			if ($user_id!=0) {
				$result_data=array('user_id'=>$user_id,'date'=>date("Y-m-d"),'heure'=>date("H"),'nbre_vue'=>1);
				$response=iInsert($stat_nbre_vue,$result_data);
				$response=json_decode($response);
				//print_r($response);
			}
	}	
}


add_action('user_register','my_function');

function my_function($user_id){
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	update_user_meta($user_id,'membership_product_date',$cn_time);
	update_user_meta($user_id,'membership_product_id','1105');

  $cn_user = get_user_by( 'id', $user_id ); 
  $cn_user->user_login;
  $upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/'.$cn_user->user_login;
	if (! is_dir($upload_dir)) {
	   mkdir( $upload_dir, 0755 );
	}
}

function upload_document(){
	global $current_user; wp_get_current_user();
	date_default_timezone_set('Europe/Paris');
	ob_start();
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_url=$upload['baseurl'];


	
	if (get_current_user_id()) {
		// if ($_GET['username']) {
		$cn_user_banner_details_coun=get_user_banner_By_Id(get_current_user_id());
		$cn_user_banner_details_count=count($cn_user_banner_details_coun);
		$membership_product_date=get_user_meta(get_current_user_id(),'membership_product_date',true);
		$membership_by=get_user_meta(get_current_user_id(),'membership_by',true);
		$membership_product_id=get_user_meta(get_current_user_id(),'membership_product_id',true);
		$membership_product_order_id=get_user_meta(get_current_user_id(),'membership_product_order_id', true);	
		$order = wc_get_order($membership_product_order_id);
		$etablissement_color=get_etablissement_color_By_Id(get_current_user_id());
		$stat_nbre_vue=get_stat_nbre_vue_By_user_id(get_current_user_id());
		$stat_nbre_vue_today=get_stat_nbre_vue_By_user_id_today(get_current_user_id());
	//print_r($nbre_vue);
		//echo $nbre_vue[0]['nbre_vue'];
		//print_r($etablissement_color);
		//echo $etablissement_color[0]['etablissement_color'];

		if ($membership_product_order_id) {
			if ($order) {
				$order->get_status();
				if($order->get_status()=='completed'){
					$completed='completed';
				}else{
					$completed='incompleted';
				}		
			}
		}
		if ($membership_product_id==1107 && $membership_by=='admin') {
			$completed='completed';
		}
		$odr_membership_product = get_post_meta($post->ID, 'odr_membership_product'.$membership_product_id, true );
		$odr_membership_product_date = get_post_meta($post->ID, 'odr_membership_product_date'.$membership_product_id, true );

			$cn_username=$_GET['username'];
			
			$cn_user_by_user = get_user_by( 'login', $cn_username); 
			$cn_user_by_user->user_login;

			$cn_user = get_user_by( 'id', get_current_user_id() ); 
		  	$cn_user->user_login;
		  	if ($_GET['myprofile']=='usename') {
				echo $url='https://qrmiam.fr/upload/?username/'.$cn_user->user_login;
				?>
				<script type="text/javascript">
					window.location.href='<?php echo $url ?>';
				</script>
				<?php
			}
		  	//if ($cn_user_by_user->user_login==$cn_user->user_login) {

				if (isset($_POST['cn_submit'])) {
					
					?>
						<?php
						if ($_FILES['cn_image']) { 
							$countfiles = count($_FILES['cn_image']['name']);
							for($i=0;$i<$countfiles;$i++){
								$newpath = str_replace('\\', '/', $upload['basedir']);
								$path=$newpath.'/'.$cn_user->user_login;
								$ext1 = explode('.', basename($_FILES['cn_image']['name'][$i]));//explode file name from dot(.) 
								$ext = end($ext1); //store extensions in the variable
								$target_path_sia =  md5(uniqid()) . "." . $ext1[count($ext1) - 1];
								$target_path_sia;
								if(move_uploaded_file($_FILES["cn_image"]["tmp_name"][$i],$path. "/" . $target_path_sia)){
									$filename = $path. "/" . $target_path_sia;
									$wp_upload_dir['url'] . '/' . basename( $filename );
									$user_doc[]=$upload_url.'/'.$cn_user->user_login.'/' . basename( $filename );
								}else{
									 $user_doc='';
								}
							}
							
							$user_doc_date= array('banner'=>'no','link'=>'','cn_from'=>$_POST['cn_from'],'cn_to'=>$_POST['cn_to']);
							$response=insert_user_doc_By_Id($user_doc,$user_doc_date);
							if ($response->success) {
								echo '<div class="umsg"><h2>Documents téléchargés avec succès</h2></div>';	
							}else{
								echo '<div class="umsg"><h2>'.$response->error.'</h2></div>';
							}
				    	}
				    
					
				}
				if (isset($_POST['cn_submit_ban'])) {
					if ($cn_user_banner_details_count==3) {
						echo '<div class="umsg"><h2>you can upload maximum 3 Banner</h2></div>';	
					}else{	
						?>
						<?php
						if ($_FILES['cn_image']) { 
							$countfiles = count($_FILES['cn_image']['name']);
							
								$newpath = str_replace('\\', '/', $upload['basedir']);
								$path=$newpath.'/'.$cn_user->user_login;
								$ext1 = explode('.', basename($_FILES['cn_image']['name']));//explode file name from dot(.) 
								$ext = end($ext1); //store extensions in the variable
								$target_path_sia =  md5(uniqid()) . "." . $ext1[count($ext1) - 1];
								$target_path_sia;
								if(move_uploaded_file($_FILES["cn_image"]["tmp_name"],$path. "/" . $target_path_sia)){
									$filename = $path. "/" . $target_path_sia;
									$wp_upload_dir['url'] . '/' . basename( $filename );
									$user_doc[]=$upload_url.'/'.$cn_user->user_login.'/' . basename( $filename );
								}else{
									 $user_doc='';
								}
							$link=$_POST['link'];
							$user_doc_date= array('banner'=>'yes','link'=>$link);
							$response=insert_user_doc_By_Id($user_doc,$user_doc_date);
							if ($response->success) {
								echo '<div class="umsg"><h2>Banner téléchargés avec succès</h2></div>';	
							}else{
								echo '<div class="umsg"><h2>'.$response->error.'</h2></div>';
							}
				    	}
				    }
					
				}
				if (isset($_POST['cn_delete'])) {
					global $wpdb;
					$cn_img_id=$_POST['cn_img_id'];
					$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
					get_current_user_id();
					if ($ss=iQuery("DELETE FROM `".$cn_user_doce."` WHERE `id`=$cn_img_id",$rs)){
						echo '<div class="umsg"><h2>Document effacé avec succès</h2></div>';	
					}else{
						echo '<div class="umsg"><h2>Il y a eu un problème</h2></div>';	
					}
				}
				if (isset($_POST['cn_update'])) {
					global $wpdb;
					$cn_img_id=$_POST['cn_img_id'];
					$cn_ordering=$_POST['cn_ordering'];
					$cn_to=$_POST['cn_to'];
					$cn_from=$_POST['cn_from'];
					$cn_user_doce = $wpdb->prefix . 'cn_user_doce';
					$users=$wpdb->prefix . 'users';
					$link=$_POST['link'];
					$cn_color=$_POST['cn_color'];
					if($_POST['cn_etablissement_color']){
						$new_etablissement_color=$_POST['cn_etablissement_color'];
					}
					else {
						$new_etablissement_color=$etablissement_color[0]['etablissement_color'];
					}
					if ($_POST['title']) {
						$title=$_POST['title'];	
					}else{
						$title='';
					}
					$result_data2=array('etablissement_color' => $new_etablissement_color);
					$result_data=array('title'=>$title,'ordering'=>$cn_ordering,'cn_from'=>$cn_from,'cn_to'=>$cn_to,'link'=>$link, 'color'=>$cn_color);

					$response=iUpdateArray($cn_user_doce,$result_data,array('`id`'=>$cn_img_id));
					$response2=iUpdateArray($users,$result_data2,array('`id`'=>get_current_user_id()));	
					$response=json_decode($response);

					if ($response->success=='success') {
						echo '<div class="umsg"><h2>mise à jour réussie</h2></div>';	
					}else{
						?>
						<div class="umsg"><h2>Il y a eu un problème</h2></div>
						<?php
					}
				}
				$cn_user_doce_details=get_user_doc_By_Id(get_current_user_id());
				$cn_user_banner_details=get_user_banner_By_Id(get_current_user_id());
				?>
				<style type="text/css" media="screen">
					.cn_btn{
						font-weight: bold;
						background: #d6d6d6;
					}
					.umsg{
						background: #fff;
						text-align: center;
						 padding: 20px;
					    margin: 0 auto;
					}
					.cn_upload	{
						width: 100%;
					    background: #fff;
					    padding: 20px;
					    margin: 0 auto;
					    border-radius: 10px;
					}
					.cn_title	{
						width: 100%;
					    background: #fff;
					    padding: 20px;
					    margin: 0 auto;
					    border-radius: 10px;
					}
					.Uploaded_doc{
						background: #fff;
					    padding: 20px;
					        margin: 15px auto;
					    border-radius: 10px;
					}
					   .cn_model{
				      left:0!important;
				      top: 0px!important;
				      width: 100%!important;
				      height: 100%!important;
				      position: fixed!important;
				      background: rgba(0, 0, 0, 0.5)!important;
				      z-index: 999999 !important; 
				      display: block;
				      overflow-y: scroll; 
				      display: none;
				    }

				    .cn_model_body{
				        position: relative;
				    	width: 70%;
				    	top: 5%;
				    	left: 0px;
				    	right: 0px;
				    	margin: 0px auto;
				    	z-index: 999999!important; 
				    	border-radius: 3px;
				    }
				    .cn_close {
				        cursor: pointer;
				        font-weight: bold;
				        color: #fff;
				    }
				    .cn_input::placeholder {color: #ccc!important;}
				    .cn_input{
				    	    margin-right: 1px;
				    	float: left;
					    width: 80px;
					    border: none;
					    border-bottom: 1px solid #c0c4d4;
					    outline: 0;
					    -webkit-transition: all .35s;
					    transition: all .35s;
					    padding: 10px 15px;
					    background: #d2d2d2;
					    color: #676d8a;
					    height: 30px;
				    }
				     .mylod{
				      left:0!important;
				      top: 0px!important;
				      width: 100%!important;
				      height: 100%!important;
				      position: fixed!important;
				      background: rgba(0, 0, 0, 0.5)!important;
				      z-index: 999999 !important; 
				      display: none;
				    }
				    .cn_close_blck{
				    	color: #000!important;
				    }
				</style>
				<script type="text/javascript">
					(function( $ ) {
						'use strict';
						$( document ).ready(function() {
							$('.cn_cbtn').click(function() {
								if ($('.cn_image').val()) {
									$(".mylod").show();
								}
							});
							$('.cn_cbtn_ban').click(function() {
								if ($('.cn_image_ban').val()) {
									$(".mylod").show();
								}
							});
							// $('.cn_cbtn').click(function() {
							// 	$(".mylod").show();
							// });
							
							$('.cn_close').click(function() {
								$(".cn_model").hide();
								jQuery('.cn_new_img').attr('src','');
								jQuery('.cn_new_PDF').attr('data','');
								jQuery('.cn_new_PDF').attr('width','');
								jQuery('.cn_new_PDF').attr('height','');
							});

							$('.open_cn_model').click(function() {
								$('.mylod').show();
									var open_cn_model_id = $(this).attr('data-target');
									$(open_cn_model_id).show();
								setTimeout(function() {
									$('.mylod').hide();
								}, 1000);
							});
						})
					})( jQuery );
					function open_img(img){
						jQuery('.cn_new_img').attr('src',img);
						jQuery(".cn_model").show();
					}
					function open_PDF(img){
						jQuery('.cn_new_PDF').attr('data',img);
						jQuery('.cn_new_PDF').attr('width','100%');
						jQuery('.cn_new_PDF').attr('height','500px');
						jQuery(".cn_model").show();
					}
				</script>
				<div class="user-registration ur-frontend-form ur-frontend-form--flat">

					<form  method="post" accept-charset="utf-8" enctype="multipart/form-data">
						<div class="cn_title">
							<?php
							

							 //global $current_user; wp_get_current_user();
								if ( is_user_logged_in() ) { 
 							//echo 'Username: ' . $current_user->user_login . "\n"; echo 'User display name: ' . $current_user->display_name . "\n"; 
 						} 
								else { wp_loginout(); } 


								$cn_user_by_user = get_user_by( 'login', $current_user->user_login); 
								$cn_user_by_user->user_login;
								$cn_user_doc=get_user_doc_By_Id($cn_user_by_user->ID);
								$cn_user_banner_details=get_user_banner_By_Id($cn_user_by_user->ID);
								$cn_all_user_meta=get_user_meta($cn_user_by_user->ID);


								

								?><h2 style="color:<?php echo $etablissement_color[0]['etablissement_color']; ?>" ><center><?php echo $cn_all_user_meta['user_registration_input_box_1591092586428'][0]; ?></center></h2>

									

								
									
									<div style="overflow: hidden; margin-bottom: 5px;">
									<label>Couleur du nom de l'établissement </label>	
									<input style="!important;" type="color" name="cn_etablissement_color" value="<?php echo $etablissement_color[0]['etablissement_color']; ?>">	
									</div>
									<div>
										<button type="submit" class="cn_btn " name="cn_update">Mise à jour</button>	
									</div>
							
						</div>
					</form><br>
					<form  method="post" accept-charset="utf-8" enctype="multipart/form-data">
						<div class="cn_upload">
							

							<?php
								if ($completed=='completed') {
									$date = strtotime($membership_product_date);
									$new_date = strtotime('+ 1 year', $date);
									$cn_date=date('Y-m-d', $new_date);
									if ($new_date >= time()) {

										$cn_all_user_meta=get_user_meta($cn_user_by_user->ID);
										?>

										<div style="overflow: hidden; margin-bottom: 5px;">

											
											<label>Ajouter une image (jpg, png, PDF)&nbsp;</label>
											<!-- <input type="file" name="cn_image" class="cn-form-control cn_image" placeholder="Image"  accept="image/*" required="required"> -->
											<input type="file" name="cn_image[]" multiple class="cn-form-control cn_image" placeholder="Image"  accept="application/pdf,image/*" required="required">
										</div>
<!-- 										<div style="overflow: hidden; margin-bottom: 5px;">
											<label>Heure de début&nbsp;&nbsp;</label>
											<input style="float: none;" max="23" type="text" name="cn_from" class="cn_input" value="<?php echo $value['cn_from']; ?>" placeholder="Ex. 02:00">	
											<label style="margin-left: 2px;">HH:MM</label>
										</div>
										<div style="overflow: hidden; margin-bottom: 5px;">
											<label>Heure de fin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input style="float: none;" max="23" type="text" name="cn_to" class="cn_input" value="<?php echo $value['cn_to']; ?>" placeholder="Ex. 05:30">	
											<label style="margin-left: 2px;">HH:MM</label>
										</div> -->
										<br>
										<?php
									}
								}else{
									?>
									<div style="overflow: hidden; margin-bottom: 5px;">
											<label>Ajouter une image (jpg, png)&nbsp;</label>
											<!-- <input type="file" name="cn_image" class="cn-form-control cn_image" placeholder="Image"  accept="image/*" required="required"> -->
											<input type="file" name="cn_image[]" multiple class="cn-form-control cn_image" placeholder="Image"  accept="image/*" required="required">
										</div>
									<?php
								}



							?>
							
							<button type="submit" class="cn_btn cn_cbtn" name="cn_submit">Ajouter</button>
						</div>
					</form>
							<?php
								if ($completed=='completed') {
									$date = strtotime($membership_product_date);
									$new_date = strtotime('+ 1 year', $date);
									$cn_date=date('Y-m-d', $new_date);
									if ($new_date >= time()) {
										?>
										<form  method="post" accept-charset="utf-8" enctype="multipart/form-data" style="    margin-top: 10px;">
											<div class="cn_upload">
												<div style="overflow: hidden; margin-bottom: 5px;">
													<label>Télécharger une bannière(jpg, png)&nbsp;</label>
													<input type="file" name="cn_image" class="cn-form-control cn_image cn_image_ban" placeholder="Image"  accept="image/*" required="required">
												</div>
												<div style="overflow: hidden; margin-bottom: 5px;">
													<label>Lien de la bannière</label>
													<input type="text" name="link" class="cn-form-control " placeholder="Lien" style="height: 30px">
												</div>
												<button type="submit" class="cn_btn cn_cbtn_ban" name="cn_submit_ban">Ajouter</button>
											</div>
										</form>
										<div class="Uploaded_doc">
											<h2>Bannières en ligne</h2>
											<?php
												if ($cn_user_banner_details) {
													foreach ($cn_user_banner_details as $banner) {
														?>
														<hr>
														<div class="et_pb_row et_pb_row_1 et_pb_row_4col">
															<div class="et_pb_column et_pb_column_1_3 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough">
																<a href="javascript: void(0);" onclick="open_img('<?php echo $banner['file']; ?>');"><img src="<?php echo $banner['file']; ?>" alt="" style="width: 100%;">	</a>												
															</div>    
															<div class="et_pb_column et_pb_column_1_3 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough">
																<form method="post" accept-charset="utf-8" style="    margin-bottom: 10px;">
																	<input type="hidden" name="cn_img_id" value="<?php echo $banner['id']; ?>">
																	<div class="" style="overflow: hidden; margin-bottom: 5px;">
																		<label>Ordre</label>
																		<label style="float: right !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
																		<input style="float: right !important;" type="number" name="cn_ordering" class="cn_input" value="<?php echo $banner['ordering']; ?>">	
																	</div>
																	<div class="" style="overflow: hidden; margin-bottom: 5px;">
																		<label>Lien</label>
																		<label style="float: right !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
																		<input style="float: right !important;width: 70%;" type="text" name="link" class="cn_input" placeholder="Lien" value="<?php echo $banner['link']; ?>">	
																	</div>
																	<div>
																		<button type="submit" class="cn_btn " name="cn_update">Mise à jour</button>	
																	</div>
																</form>
															</div>    
															<div class="et_pb_column et_pb_column_1_3 et_pb_column_3  et_pb_css_mix_blend_mode_passthrough">
																<form method="post" accept-charset="utf-8">
																	<input type="hidden" name="cn_img_id" value="<?php echo $banner['id']; ?>">
																	<button type="submit" class="cn_btn" name="cn_delete">Effacer</button>
																</form>
															</div>    
														</div>
											<?php
													}}else{
														?>
														<tr>
															<td colspan="2" >
																<h2 style="text-align: center;">Pas de bannière pour le moment</h2>
															</td>
														</tr>
														<?php
													}
													?>
										</div>
										<?php
									}
								}
							?>
							
					<div class="Uploaded_doc">
						<h2>Documents en ligne</h2>
						<?php 
						$vue_total=$stat_nbre_vue[0]['somme'];
						if ($vue_total!=0) {
							echo "Vos cartes et menus ont été vues <b>".$stat_nbre_vue_today[0]['somme']."</b> fois aujourd'hui et <b>".$stat_nbre_vue[0]['somme']."</b> fois au total. - ";?><a href="#" title="En cours de développement">Statistiques</a><?php
						}
						
							if ($cn_user_doce_details) {
								foreach ($cn_user_doce_details as $value) {
									$fileext=explode('.', $value['file']);

									?>
									<hr>
									<div class="et_pb_row et_pb_row_1 et_pb_row_4col">
										<div class="et_pb_column et_pb_column_1_3 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough">
											<?php 
											if ($fileext[2]=='pdf') {
												?>
												<a target="_blank" href="<?php echo $value['file']; ?>?#zoom=0">
													<img src="https://qrmiam.fr/wp-content/uploads/2020/06/pdf_tb.png" alt="" style="width: 100%;">
													<p style="    text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p>
														</a>	
												<?php
											}else{
												?>
												<a href="javascript: void(0);" onclick="open_img('<?php echo $value['file']; ?>');"><img src="<?php echo $value['file']; ?>" alt="" style="width: 100%;">	
												<p style="text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p>
											</a>												
												<?php
											}
											 ?>
											
											
										</div>    
										<div class="et_pb_column et_pb_column_1_3 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough">
											<form method="post" accept-charset="utf-8" style="    margin-bottom: 10px;">
												<input type="hidden" name="cn_img_id" value="<?php echo $value['id']; ?>">
												<div class="" style="overflow: hidden; margin-bottom: 5px;">
													<label>Ordre</label>
													<label style="float: right !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
													<input style="float: right !important;" type="number" name="cn_ordering" class="cn_input" value="<?php echo $value['ordering']; ?>">	
												</div>
												<?php
												if ($completed=='completed') {
													//$date = strtotime($membership_product_date);
													//$new_date = strtotime('+ 1 year', $date);
													//$cn_date=date('Y-m-d', $new_date);
													//if ($new_date >= time()) {
														//if ($fileext[2]=='pdf') {?>
														<div style="overflow: hidden; margin-bottom: 5px;">
															<label>Titre</label>
															<input style="float: right !important;width: 135px;" type="text" name="title" class="cn_input" value="<?php echo $value['title']; ?>" placeholder="title">	
														</div>
														<div style="overflow: hidden; margin-bottom: 5px;">
															<label>Couleur du titre</label>	
															<input style="float: right !important;" type="color" name="cn_color" value="<?php echo $value['color']; ?>">	
														</div>
														<?php //}
														?>
														<div style="overflow: hidden; margin-bottom: 5px;">
															<label>Heure début</label>
															<label style="float: right !important;margin-left: 2px;">HH:MM</label>
															<input style="float: right !important;" type="text" name="cn_from" class="cn_input" value="<?php echo $value['cn_from']; ?>" placeholder="Ex. 02:00">	
														</div>
														<div style="overflow: hidden; margin-bottom: 5px;">
															<label>Heure fin</label>
															<label style="float: right !important;margin-left: 2px;">HH:MM</label>
															<input style="float: right !important;" type="text" name="cn_to" class="cn_input" value="<?php echo $value['cn_to']; ?>" placeholder="Ex. 05:30">	
														</div>
															<?php //if ($fileext[2]=='pdf') {?>

														<?php //}
														?>
												<?php
														//}
													}
												?>
												<div>
													<button type="submit" class="cn_btn " name="cn_update">Mise à jour</button>	
												</div>
											</form>
										</div>    
										<div class="et_pb_column et_pb_column_1_3 et_pb_column_3  et_pb_css_mix_blend_mode_passthrough">
											<form method="post" accept-charset="utf-8">
												<input type="hidden" name="cn_img_id" value="<?php echo $value['id']; ?>">
												<button type="submit" class="cn_btn" name="cn_delete">Effacer</button>
											</form>
										</div>    
									</div>
						<?php
								}}else{
									?>
									<tr>
										<td colspan="2" >
											<h2 style="text-align: center;">Aucun document pour le moment</h2>
										</td>
									</tr>
									<?php
								}
								?>
					</div>
					<div class="mylod" style="">
					      <img src="https://qrmiam.fr/wp-content/uploads/2020/06/loder.gif" style="width: 200px;position: fixed;top: 40%;left: 0px;right: 0px;margin: 0px auto;z-index: 9999999999;border-radius: 3px;">  
					</div>
				</div>
				
		
		<?php
			// }else{
			// 	echo '<h3 style="text-align: center;">This is not your page</h3>';
			// }
		// }else{
		// 	echo '<h3 style="text-align: center;">invalid user</h3>';
		// }
	}else{
		echo '<h3 style="text-align: center;">Vous devez vous authentifier pour accéder à la page</h3>';
	}
		$ReturnString = ob_get_contents();
		ob_end_clean();
		return $ReturnString;
}
add_shortcode('user_document_upload', 'upload_document');



function cn_public_page(){
	date_default_timezone_set('Europe/Paris');
	ob_start();
	global $wpdb;
	$k=0;
	$stat_nbre_vue_table=$wpdb->prefix . 'stat_nbre_vue';
	
	 //$cn_username=$_GET['username'];
	// $nbre_vue=get_nbre_vue_By_Username($cn_username);
	// //print_r($nbre_vue);
	// $result_data2=array('nbre_vue' => $nbre_vue[0]['nbre_vue']+1);
	// $response2=iUpdateArray($users,$result_data2,array('`display_name`'=>$cn_username));	
	// $response=json_decode($response);
	
	//insert_stat_nbre_vue();

	if ($_GET['username']) {

		$user_id=get_userID_By_username($_GET['username']);
		//print_r($user_id);
		$user_id=$user_id[0]['ID'];
		$stat_nbre_vue=get_stat_nbre_vue_By_user_id_now($user_id);
		$date="'".date("Y-m-d")."'";
		$heure=date("H");
		//echo date("H");
		//print_r($stat_nbre_vue);
		if($stat_nbre_vue){//Si il existe déja un champ pour l'user_id

				if($stat_nbre_vue[0]['date']==date("Y-m-d")){//Si il existe déja un champ pour le jour

					if($stat_nbre_vue[0]['heure']==date("H")){

						$result_data=array('nbre_vue'=>$stat_nbre_vue[0]['nbre_vue']+1);
						$response=iUpdateArrayInt($stat_nbre_vue_table,$result_data,array('user_id'=>$user_id." AND ",'date'=>$date." AND ",'heure'=>$heure));
						$response=json_decode($response);		
					}	
				}
		}
		else {
			insert_stat_nbre_vue();
		}
	}

	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_url=$upload['baseurl'];
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_date=date('m/d/Y h:i:sa', time());
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"H:i");
		if ($_GET['username']) {
			$cn_username=$_GET['username'];
			//$username=$_GET['username'];
			$cn_user_by_user = get_user_by( 'login', $cn_username); 
			$cn_user_by_user->user_login;
			$cn_user_doc=get_user_doc_By_Id($cn_user_by_user->ID);
			$cn_user_banner_details=get_user_banner_By_Id($cn_user_by_user->ID);
			$cn_all_user_meta=get_user_meta($cn_user_by_user->ID);
			$etablissement_color=get_etablissement_color_By_username($cn_username);
			//print_r(get_etablissement_color_By_username("victor_test"));
			//echo $cn_username;
				?>
				<style type="text/css" media="screen">
					/*.landscape img {
					    height: 165px;
					    width: 100%;
					}*/
					.umsg{
						background: #fff;
						text-align: center;
						 padding: 20px;
					    margin: 0 auto;
					}
					.cn_upload	{
						width: 300px;
					    background: #fff;
					    padding: 20px;
					    margin: 0 auto;
					    border-radius: 10px;
					}
					.Uploaded_doc{
						background: #fff;
					    padding: 20px;
					        margin: 15px auto;
					    border-radius: 10px;
					    margin-bottom: 50px;
					    display: block;
					}
					   .cn_model{
				      left:0!important;
				      top: 0px!important;
				      width: 100%!important;
				      height: 100%!important;
				      position: fixed!important;
				      background: rgba(0, 0, 0, 0.5)!important;
				      z-index: 999999 !important; 
				      display: block;
				      overflow-y: scroll; 
				      display: none;
				    }

				    .cn_model_body{
				        position: relative;
				    	width: 70%;
				    	top: 5%;
				    	left: 0px;
				    	right: 0px;
				    	margin: 0px auto;
				    	z-index: 999999!important; 
				    	border-radius: 3px;
				    }
				    .cn_close {
				        cursor: pointer;
				        font-weight: bold;
				        color: #fff;
				    }
				    .cn_col{
				    	width: 33%;
				    	float: left;
				    	padding: 10px;

				    }
				    .fancybox-container {
    				z-index: 1000000 !important;
					}
				</style>
<!-- 				<script type="text/javascript">
					(function( $ ) {
						'use strict';
						$( document ).ready(function() {
							$('.cn_close').click(function() {
								$(".cn_model").hide();
								jQuery('.cn_new_img').attr('src','');
								jQuery('.cn_new_PDF').attr('data','');
								jQuery('.cn_new_PDF').attr('width','');
								jQuery('.cn_new_PDF').attr('height','');
							});

							$('.open_cn_model').click(function() {
								$('.mylod').show();
									var open_cn_model_id = $(this).attr('data-target');
									$(open_cn_model_id).show();
								setTimeout(function() {
									$('.mylod').hide();
								}, 1000);
							});
						})
					})( jQuery );
					function open_img(img){
						jQuery('.cn_new_img').attr('src',img);
						jQuery(".cn_model").show();
					}
					function open_PDF(img){
						jQuery('.cn_new_PDF').attr('data',img);
						jQuery('.cn_new_PDF').attr('width','100%');
						jQuery('.cn_new_PDF').attr('height','500px');
						jQuery(".cn_model").show();
					}
				</script> -->

									<script>
    									lightbox.option({
    									'alwaysShowNavOnTouchDevices':true,
      									'resizeDuration': 200,
      									'disableScrolling':false,
      									'showImageNumberLabel': false,
      									'wrapAround': true
    									})

									</script>


									<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
									<script src="/js/jquery.fancybox.min.js"></script>

									<script type="text/javascript">
										$('[data-fancybox="gallery"]').fancybox({
										buttons: ["zoom","thumbs","close"]
    //"share",
    //"slideShow",
    //"fullScreen",
    //"download",
    
  
										});
									</script>

				
					<div class="Uploaded_doc">

						<?php
						
						?>
						<h2 style="color:<?php echo $etablissement_color[0]['etablissement_color']; ?>" ><center><?php echo $cn_all_user_meta['user_registration_input_box_1591092586428'][0]; ?></center></h2>	
						
						<br>
							<?php
							if ($cn_time=='00:00') {
								$cn_time='24:00';
							}
								if ($cn_user_doc) {
									
								foreach ($cn_user_doc as $value) {
									?> <?php
									$fileext=explode('.', $value['file']);

									if ($value['cn_from']!='') {
										if ($value['cn_to']=='00:00') {
											$value['cn_to']='24:00';
										}
										if ($cn_time>$value['cn_from'] && $cn_time<$value['cn_to']) {
											?>
												<div cn_time="<?php echo $cn_time ?>" cn_to="<?php echo $value['cn_to']?>" cn_from="<?php echo $value['cn_from'] ?>" class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">
													<div class="et_pb_gallery_image landscape">
														<?php 
														if ($fileext[2]=='pdf') {
															?>
															<a href="<?php echo $value['file']; ?>?#zoom=0">
																<img src="https://qrmiam.fr/wp-content/uploads/2020/06/pdf_tb.png" alt="" style="width: 100%;">	
																<p style="    text-align: center;"><?php echo htmlspecialchars($value['title']); ?></p>
															</a>	
															<?php
														}else{?>


																			<a data-fancybox="gallery" data-options='{"buttons": ["zoom","thumbs","close"]}' href="<?php echo $value['file']; ?>"><img src="<?php echo $value['file']; ?>"><p style="   margin-top: 17px; text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p></a>


<!-- 															<a href="<?php echo $value['file']; ?>" title="">
																<img src="<?php echo $value['file']; ?>" srcset="<?php echo $value['file']; ?> 479w, <?php echo $value['file']; ?> 480w" sizes="(max-width:479px) 479px, 100vw">
																<span class="et_overlay"></span>
															</a> -->	
														<?php }?>
														
													</div>
												</div>
											<?php
										}
									}else{
									?>
										
												<?php 
														if ($fileext[2]=='pdf') {
															?>
															<div class="et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">
																<div class="landscape">
																	<a target="_blank" href="<?php echo $value['file']; ?>?#zoom=0">
																		<img src="https://qrmiam.fr/wp-content/uploads/2020/06/pdf_tb.png" alt="" style="width: 100%;">	
																		
																		<p style="    text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p>
																	</a>	
																</div>
															</div>
															<?php
														}else{?>
															<div class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">
																<div class="et_pb_gallery_image landscape">


																				<a data-fancybox="gallery" data-options='{"buttons": ["thumbs","close"]}' href="<?php echo $value['file']; ?>"><img src="<?php echo $value['file']; ?>"><p style="   margin-top: 17px; text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p></a>
<!-- 																	<a href="<?php echo $value['file']; ?>" title="">
																		<img src="<?php echo $value['file']; ?>" srcset="<?php echo $value['file']; ?> 479w, <?php echo $value['file']; ?> 480w" sizes="(max-width:479px) 479px, 100vw">
																		<span class="et_overlay"></span>
																		<p style="   margin-top: 17px; text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo $value['title']; ?></p>
																	</a>	 -->
																</div>
															</div>
														<?php }?>
											
									<?php
									}
									
								}}else{
									?>
											<h2 style="text-align: center;">Aucun document disponible</h2>
									<?php
								}?>
						
					</div>

				
				
		
		<?php
			
		}else{
			echo '<h3 style="text-align: center;">utilisateur non autorisé</h3>';
		}
	
		$ReturnString = ob_get_contents();
		ob_end_clean();
		return $ReturnString;
}

add_shortcode('user_public_page', 'cn_public_page');


function cn_model(){
	?>
	<div class="cn_model" id="cn_model" style="display: none">
		<div class="cn_model_body">
			<div class="cn_card mb-4">
				<div class="cn_card-header"><i class="cn_close pull-right">X</i></div>
				<div id="cn_model_body" class="cn_card-body" style="text-align: center;">
					<img class="cn_new_img" src="" alt="">
					<!-- <iframe class="cn_new_PDF" src=""></iframe> -->
					<object class="cn_new_PDF" data="" type="application/pdf"></object>

				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action('wp_head', 'cn_model');


function custom_remove_user( $user_id ) {
  $cn_user = get_user_by( 'id', $user_id ); 
  $cn_user->user_login;
  $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/'.$cn_user->user_login;
    delete_directory($upload_dir);
}
add_action( 'delete_user', 'custom_remove_user', 10 );

function delete_directory($dirname) {
    		if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}






 	function product_start_date_meta_box() {
	    add_meta_box('product_start_date_meta_box', 'Membership Product','product_start_date_meta_box_content' ,'product','side','high');
	}
	function product_start_date_meta_box_content( $post ) {
		$latest_issue_product_id=get_option('latest_issue_product_id');
		wp_nonce_field( plugin_basename( __FILE__ ), 'product_start_date_meta_box_content_nonce' );
		$post->ID;
		 $membership_product = get_post_meta($post->ID, 'membership_product', true );
	  ?>
		<input type="radio" id="yes" name="membership_product" value="yes" <?php if($membership_product=='yes'){echo "checked";} ?>>
		<label for="yes">Yes</label><br>
		<input type="radio" id="No" name="membership_product" value="no" <?php if($membership_product=='no'){echo "checked";} ?>>
		<label for="No">No</label><br>
	  <?php
	  
	  
	}

	function product_start_date_meta_box_save( $post_id ) {
	  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	  return;
	  if ( !wp_verify_nonce( $_POST['product_start_date_meta_box_content_nonce'], plugin_basename( __FILE__ ) ) )
	  return;

	  if ( 'page' == $_POST['post_type'] ) {
	    if ( !current_user_can( 'edit_page', $post_id ) )
	    return;
	  } else {
	    if ( !current_user_can( 'edit_post', $post_id ) )
	    return;
	  }
	  update_post_meta($post_id, 'membership_product', $_POST['membership_product']);
	}
add_action( 'add_meta_boxes', 'product_start_date_meta_box' );
add_action( 'save_post', 'product_start_date_meta_box_save' );



add_shortcode( 'membership_product' , 'membership_product' );

function membership_product(){
	ob_start();
  	$args = array(
		        'posts_per_page'   => -1, 
		        'post_type' => 'product',
		        'meta_key'=>'membership_product',
		        'meta_value'=>'yes',
		        'orderby'  => 'id',
		        'order'    => 'ASC',
		        );
  	if (get_current_user_id()) {
		$membership_product_date=get_user_meta(get_current_user_id(),'membership_product_date',true);
		$membership_by=get_user_meta(get_current_user_id(),'membership_by',true);
		$membership_product_id=get_user_meta(get_current_user_id(),'membership_product_id',true);
		$membership_product_order_id=get_user_meta(get_current_user_id(),'membership_product_order_id', true);	
		$order = wc_get_order($membership_product_order_id);
		if ($membership_product_id==1105) {
			$completed='completed';
		}else{
			if ($membership_product_order_id) {
				if ($order) {
					$order->get_status();
					if($order->get_status()=='completed'){
						$completed='completed';
					}else{
						$completed='incompleted';
					}		
				}
				
			}
		}
		if ($membership_product_id==1107 && $membership_by=='admin') {
			$completed='completed';
		}
		
	
		$odr_membership_product = get_post_meta($post->ID, 'odr_membership_product'.$membership_product_id, true );
		$odr_membership_product_date = get_post_meta($post->ID, 'odr_membership_product_date'.$membership_product_id, true );
  	}
  	
  	?>
  	<style type="text/css" media="screen">
  	.cn_none{display: none}
  	div#page-container {padding: 0 !important;margin: 0px !important;}
  	a.et_pb_button {border-radius: 50px;border-color: #2f688d !important;background: #2f688d !important;}
  	a.et_pb_button:hover {
    padding: 10px 15px !important;
}
  	</style>
  	<div class="et_pb_row et_pb_row_1 et_pb_equal_columns">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough et-last-child">
					<div class="et_pb_with_border et_pb_module et_pb_pricing_tables_0 et_pb_pricing clearfix et_pb_pricing_3 et_pb_second_featured et_pb_pricing_no_bullet et_had_animation" style="">
						<div class="et_pb_pricing_table_wrap">
  	<?php
   		$cn_product = new WP_Query( $args );
   		if($cn_product->have_posts() ) {
   			$cnsn=0;
			while ($cn_product->have_posts()) { $cn_product->the_post();$product_id=get_the_ID();
				$cnsn++;
				global $product;
				?>
					<div class="et_pb_pricing_table et_pb_pricing_table_0 <?php if($cnsn==2){ echo 'et_pb_featured_table';} ?>">
						<div class="et_pb_pricing_heading">
							<h2 class="et_pb_pricing_title"><?php echo get_the_title($product_id); ?></h2>
						</div> <!-- .et_pb_pricing_heading -->
						<div class="et_pb_pricing_content_top">
							<span class="et_pb_et_price">
								<span class="et_pb_dollar_sign" style="margin-left: -11px;">
									<?php
									if ($product_id!=1178) {
										echo get_woocommerce_currency_symbol();	
									}
									  ?>
								</span>
								<span class="et_pb_sum">
									<?php  if ($product_id!=1178) {echo $product->get_price();}else{ echo '<span style="font-size: 25px;">SUR DEMANDE</span>';} ?>
								</span>
								<span class="et_pb_frequency">
									<?php  if ($product_id!=1178) {?>
									<span class="et_pb_frequency_slash">/</span>an
								<?php } ?>
								</span>
							</span>
						</div> <!-- .et_pb_pricing_content_top -->
						<div class="et_pb_pricing_content">
							<!-- <ul class="et_pb_pricing">
								<li><span>Consectetur adipiscing elit</span></li>
								<li><span>Ut massa quam auctor sapien</span></li>
								<li><span>Proin facilisis orci metus feugiat</span></li>
								<li><span>Non sagittis lorem congue</span></li>
							</ul> -->
							<?php the_content() ?>
						</div> <!-- .et_pb_pricing_content -->
						<div class="et_pb_button_wrapper">
							<?php
							  if ($product_id!=1178) { // Si produit différent de commerce

							  	if (is_user_logged_in()==1) { // Si loggé

							  		//echo "debug";

							  		if($membership_product_id==1105) { //Si compte gratuit

							  			if($product_id==1105) { // Si produit gratuit

							  				//Pas de bouton
							  			}


							  			if($product_id==1107){ // Si produit premium

							  				//echo "debug";

							  				echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="?add-to-cart='.$product_id.'" rel="nofollow" data-product_id="'.$product_id.'" data-icon="$">SOUSCRIRE</a>';
							  			}

							  		}

							  		if($membership_product_id==1107) { // Si compte premium

							  			if($product_id==1105) { // Si produit gratuit

							  			}


							  			if($product_id==1107) { // Si produit premium

							  				echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="#" rel="nofollow" data-product_id="1106" data-icon="$">Déjà abonné</a>';

							  			}


							  		}

							  	}

							  	else { // Si pas loggé

							  		if($product_id==1105) {

							  			echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="https://qrmiam.fr/#formulaire" rel="nofollow" data-product_id="1106" data-icon="$">S\'inscrire</a>';
							  		}
							  		else {

							  		echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="https://qrmiam.fr/#formulaire" rel="nofollow" data-product_id="1106" data-icon="$">S\'inscrire</a>';

							  		}
							  	}



								// if ($completed=='completed') {
								// 	if ($membership_product_id==$product_id) {
								// 		$date = strtotime($membership_product_date);
								// 		$new_date = strtotime('+ 1 year', $date);
								// 		$cn_date=date('Y-m-d', $new_date);
								// 		if ($new_date >= time()) {

											
								// 			echo'<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="#" rel="nofollow" data-product_id="1106" data-icon="$">Déjà abonné</a>';
								// 		}else{
								// 			echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="?add-to-cart='.$product_id.'" rel="nofollow" data-product_id="'.$product_id.'" data-icon="$">SOUSCRIRE</a>';		
								// 		}
								// 	}else{
								// 	echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="?add-to-cart='.$product_id.'" rel="nofollow" data-product_id="'.$product_id.'" data-icon="$">SOUSCRIRE</a>';		
								// 	}
								// }
								// else{
								// 	echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="?add-to-cart='.$product_id.'" rel="nofollow" data-product_id="'.$product_id.'" data-icon="$">SOUSCRIRE</a>';		
								// }
							






							}
							else{ //Si produit == commerce
								echo '<a class="et_pb_button et_pb_custom_button_icon et_pb_pricing_table_button" href="https://qrmiam.fr/forment" rel="nofollow" > CONTACT </a>';									
							}	
							?>
						
							
						</div>
					</div>

				<?php 
			}
		}
		?>
			
							
						</div>
					</div>
				</div>
			</div>
		<?php

		$ReturnString = ob_get_contents(); ob_end_clean(); 
 		return $ReturnString;
}

add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
function my_custom_checkout_field_update_order_meta( $order_id ) {
	$cn_order=wc_get_order($order_id);
	$cncc = get_post_meta($order_id);
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	$sn=0;
	foreach ( $cn_order->get_items() as $item_id => $item ) {
		$sn++;
		$product_id = $item->get_product_id(); //Get the product ID
		$quantity = $item->get_quantity(); //Get the product QTY
		$product_name = $item->get_name(); //Get the product NAME
		$product = $item->get_product();
		$description = $product->get_description();
		 $cn_product_title=$product->get_title(); //Get the product NAME
		$membership_product = get_post_meta($product_id, 'membership_product', true );
		if ($membership_product=='yes') {
			update_user_meta(get_current_user_id(),'membership_product_date',$cn_time);
			update_user_meta(get_current_user_id(),'membership_product_id',$product_id);
			update_user_meta(get_current_user_id(),'membership_product_order_id',$order_id);
			update_post_meta($order_id, 'odr_membership_product'.$product_id, $membership_product);
			update_post_meta($order_id, 'odr_membership_product_date'.$product_id, $cn_time);
		}
	}
}




add_shortcode( 'cn_user_banner' , 'user_banner' );

function user_banner(){
	ob_start();
	if ($_GET['username']) {
		$cn_username=$_GET['username'];
		$cn_user_by_user = get_user_by( 'login', $cn_username); 
		$cn_user_by_user->user_login;
		$cn_user_doc=get_user_doc_By_Id($cn_user_by_user->ID);
		$cn_user_banner_details=get_user_banner_By_Id($cn_user_by_user->ID);

		$membership_product_date=get_user_meta($cn_user_by_user->ID,'membership_product_date',true);
		$membership_by=get_user_meta($cn_user_by_user->ID,'membership_by',true);
		$membership_product_id=get_user_meta($cn_user_by_user->ID,'membership_product_id',true);
		$membership_product_order_id=get_user_meta($cn_user_by_user->ID,'membership_product_order_id', true);	
		$order = wc_get_order($membership_product_order_id);
		if ($membership_product_id==1105) {
			//$completed='completed';
		}else{
			if ($membership_product_order_id) {
				if ($order) {
					$order->get_status();
					if($order->get_status()=='completed'){
						$completed='completed';
					}else{
						$completed='incompleted';
					}		
				}
				
			}
		}
		if ($membership_product_id==1107 && $membership_by=='admin') {
			$completed='completed';
		}



	}
	$cn_all_banner=get_option('cn_all_banner');
	$cn_all_banner_link=get_option('cn_all_banner_link');
	?>
	<div class="et_pb_row et_pb_row_0">
		<!-- <div class="et_pb_column et_pb_column_1 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough"> -->
		<?php if ($completed=='completed'){ 
				if (count($cn_user_banner_details==1)) {
					$cn_user_banner_details[1]=$cn_user_banner_details[0];
					$cn_user_banner_details[0]=array();
				}
				if ($cn_user_banner_details[0]['file']) {
					$cn_link0=$cn_user_banner_details[0]['link'];
					$cn_link_file0=$cn_user_banner_details[0]['file'];
					$target0='_blank';
				}else{
					$cn_link='#';
					$target='_self';
					$cn_link0='';
					$cn_link_file0='';
				}
				if ($cn_user_banner_details[1]['file']) {
					$cn_link1=$cn_user_banner_details[1]['link'];
					$cn_link_file1=$cn_user_banner_details[1]['file'];
					$target1='_blank';
				}else{
					$cn_link='#';
					$target1='_self';
					$cn_link1='';
					$cn_link_file1='';
				}
				if ($cn_user_banner_details[2]['file']) {
					$cn_link2=$cn_user_banner_details[2]['link'];
					$cn_link_file2=$cn_user_banner_details[2]['file'];
					$target2='_blank';
				}else{
					$cn_link='#';
					$target2='_self';
					$cn_link2='';
					$cn_link_file2='';
				}
				?>
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_1 et_pb_css_mix_blend_mode_passthrough">
					<a target="<?php echo $target0; ?>" href="<?php echo $cn_link0; ?>">
						<span class="et_pb_image_wrap ">
							<img src="<?php echo $cn_link_file0; ?>">
						</span>
					</a>
				</div>
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_2 et_pb_css_mix_blend_mode_passthrough">
					<a target="<?php echo $target1; ?>" href="<?php echo $cn_link1; ?>">
						<span class="et_pb_image_wrap ">
							<img src="<?php echo $cn_link_file1; ?>">
						</span>
					</a>
				</div>
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_3 et_pb_css_mix_blend_mode_passthrough">
					<a target="<?php echo $target3; ?>" href="<?php echo $cn_link2; ?>">
						<span class="et_pb_image_wrap ">
							<img src="<?php echo $cn_link_file2; ?>">
						</span>
					</a>
				</div>
			<?php 
			 }else{
			 		if ($cn_all_banner) {
			 			if ($cn_all_banner_link[0]) {
			 				$cn_link_banne0=$cn_all_banner_link[0];
			 				$img_banner0=$cn_all_banner[0];
							$target0='_blank';
						}else{
							$cn_link_banne0='#';
							$img_banner0='';
							$target0='_self';
						}
						if ($cn_all_banner_link[1]) {
			 				$cn_link_banner1=$cn_all_banner_link[1];
			 				$img_banner1=$cn_all_banner[1];
							$target1='_blank';
						}else{
							$cn_link_banner1='#';
							$img_banner1='';
							$target1='_self';
						}
						if ($cn_all_banner_link[2]) {
							$img_banner2=$cn_all_banner[2];
			 				$cn_link_banner2=$cn_all_banner_link[2];
							$target2='_blank';
						}else{
							$cn_link_banner2='#';
							$img_banner2='';
							$target2='_self';
						}
			 		?>
			 	<div class="et_pb_column et_pb_column_1_3 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough">
			 		<?php
			 		if ($img_banner0) {?>
			 			<a target="<?php echo $target0; ?>" href="<?php echo $cn_link_banne0; ?>">
							<span class="et_pb_image_wrap ">
								<img src="<?php echo $img_banner0; ?>">
							</span>
						</a>
					<?php } ?>
				</div>
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough">
					<?php
			 		if ($img_banner1) {?>
						<a target="<?php echo $target1; ?>" href="<?php echo $cn_link_banner1; ?>">
							<span class="et_pb_image_wrap ">
								<img src="<?php echo $img_banner1; ?>">
							</span>
						</a>
					<?php } ?>
				</div>
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_3  et_pb_css_mix_blend_mode_passthrough">
					<?php
			 		if ($img_banner2) {?>
						<a target="<?php echo $target2; ?>" href="<?php echo $cn_link_banner2; ?>">
							<span class="et_pb_image_wrap ">
								<img src="<?php echo $img_banner2; ?>">
							</span>
						</a>
					<?php } ?>
				</div>
			 <?php 
			 		}
			 	}
			 	?>	
		<!-- </div> -->
	</div>
	<?php
	$ReturnString = ob_get_contents(); ob_end_clean(); 
 		return $ReturnString;

}

function cn_menu(){
	add_menu_page('Upload Banner', 'Upload Banner', 'manage_options', 'cn-menu', 'cn_menu_content','dashicons-text-page',5);
}
function cn_menu_content(){
 	if (isset($_POST['cn_submit'])) {
 		update_option('cn_all_banner',$_POST['cn_all_banner']);
 		update_option('cn_all_banner_link',$_POST['cn_all_banner_link']);
 		echo '<h3 style="text-align: center;">Updated successfully</h3>';
 	}
 	$cn_all_banner=get_option('cn_all_banner');
 	$cn_all_banner_link=get_option('cn_all_banner_link');
 	?>
 	<form method="post" accept-charset="utf-8">
 		<br>
 		<br><br>
 		<label>Banner 1</label><br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[0]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[0]; ?>" placeholder="link">
  		<br><br>
  		<label>Banner 2</label><br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[1]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[1]; ?>" placeholder="link">
  		<br><br>
  		<label>Banner 3</label><br>
  		<input type="text" name="cn_all_banner[]" style="width: 500px" value="<?php echo $cn_all_banner[2]; ?>" placeholder="">
  		<input type="text" name="cn_all_banner_link[]" style="width: 500px" value="<?php echo $cn_all_banner_link[2]; ?>" placeholder="link">
  		<br><br>
  		<button type="submit" name="cn_submit" class="button">Submit</button>
 	</form>
 	<?php
}

 add_action('admin_menu','cn_menu');



add_action( 'edit_user_profile', 'cn_custom_user_profile_fields' );
 
function cn_custom_user_profile_fields( $user )
{
	// $timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	// $cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	// update_user_meta($user_id,'membership_product_date',$cn_time);
	// update_user_meta($user_id,'membership_product_id','1105');
	$membership_product_id=get_user_meta($user->ID,'membership_product_id',true);
    echo '<h3 class="heading">Update Membership plan</h3>';
    
    ?>
    
    <table class="form-table">
	<tr>
        <th><label for="contact">Membership plan</label></th>
	    <td>
	    	<select name="membership_plan">
	    		<option <?php if($membership_product_id==1105){echo 'selected="selected"';} ?> value="1105">GRATUIT</option>
	    		<option <?php if($membership_product_id==1107){echo 'selected="selected"';} ?> value="1107">PREMIUM</option>
	    	</select>
		</td>
 
	</tr>
    </table>
    
    <?php
}
 
add_action( 'edit_user_profile_update', 'wk_save_custom_user_profile_fields' );
function wk_save_custom_user_profile_fields( $user_id )
{
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	$membership_plan = $_POST['membership_plan'];
	update_user_meta($user_id,'membership_product_date',$cn_time);
	update_user_meta($user_id,'membership_by','admin');
	update_user_meta($user_id,'membership_product_id',$membership_plan);
}


add_filter('manage_users_columns', 'pippin_add_user_id_column');
function pippin_add_user_id_column($columns) {
    $columns['membership'] = 'Membership';
    return $columns;
}
 
add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
    $membership_product_id=get_user_meta($user_id,'membership_product_id',true);
	if ( 'membership' == $column_name ){
		if($membership_product_id==1105){ $cn_membership='GRATUIT';} 
	    if($membership_product_id==1107){ $cn_membership='PREMIUM';}
		return $cn_membership;
	}
    
}


// BDO FUNCTIONS - Do not erase

add_shortcode( 'gen-qrcode' , 'gen_qrcode' );

function gen_qrcode(){

    $user = wp_get_current_user();
//    return $user->display_name;
//   if($user->display_name != ""){
     if (get_current_user_id()){
    	$qrcode_url="https://qrmiam.fr/Profile/?username=" . $user->display_name;
	?><div style="text-align: center;">
	<h5 style="text-align: center;">Voici votre QRCode personnel à télécharger, clic droit sur PC ou appui long sur smartphone</h5><?php
    	echo do_shortcode( '[kaya_qrcode_dynamic ecclevel="L" align="alignnone"]' . $qrcode_url . '[/kaya_qrcode_dynamic]' );
	?></div><?php
    }
	else {
	echo '<h3 style="text-align: center;">Vous devez vous authentifier pour accéder à la page</h3>';
	     }
}

	function get_vue_hourly($user_id,$year,$month,$day){

	$stat_nbre_vue=get_stat_nbre_vue_By_user_id_And_Date_hourly($user_id,$year,$month,$day);
		if($stat_nbre_vue[0]['nbre_vue']!=0){
			for ($i=0; $i < sizeof($stat_nbre_vue); $i++) { 
				if(!$stat_nbre_vue[$i]['nbre_vue'] OR !$stat_nbre_vue[$i]['heure']){
					//echo "0";
				}
				$x[$i]=$stat_nbre_vue[$i]['heure']."h";
				$y[$i]=$stat_nbre_vue[$i]['nbre_vue'];


			}

							?>

					<canvas id="myChart"></canvas>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <canvas id="myChart" width="300" height="50"></canvas>

        <script>
              var ctx = document.getElementById("myChart");
              var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: <?php echo json_encode($x); ?>,
                      datasets: [{
                          label: 'Vues',
                          data: <?php echo json_encode($y); ?>,
                          borderColor:'rgba(255, 40, 40, 1)',
                          lineTension:0.05,
                          backgroundColor:'rgba(0, 0, 0, 0.05)'
                      }]
                  }
              });
        </script>



	<?php


		}
		else{
			echo "Pas de données.";
		}
	}


function stats_page(){


	if(isset($_POST['date'])){

			if(isset($_POST['date'])&&isset($_POST['user'])){
				$user_id=get_userID_By_username($_POST['user']);
				$user_id=$user_id[0]['ID'];

	echo "<br><h4>Statistiques de ".$_POST['user']." du ".$_POST['date']." :</h4>";
	$date=preg_split('/-/', $_POST["date"], -1, PREG_SPLIT_NO_EMPTY);
	//print_r($_POST["date"]);
	get_vue_hourly($user_id,$date[0],$date[1],$date[2]);

		
	}

	else {
	echo "<br><h4>Statistiques du ".$_POST['date']." :</h4>";
	$date=preg_split('/-/', $_POST["date"], -1, PREG_SPLIT_NO_EMPTY);
	//print_r($_POST["date"]);
	get_vue_hourly(get_current_user_id(),$date[0],$date[1],$date[2]);

	}

	}





	else {
		echo "<br><h4>Statistiques du ".date("d")."/".date("m")."/".date("Y")." :</h4>";
		get_vue_hourly(get_current_user_id(),date("Y"),date("m"),date("d"));
	}

	?>
<form action="" method="POST">
	<input type="date" id="start" name="date"
       value="<?php if ($_POST["date"]) { echo $_POST["date"]; } else { echo date("Y-m-d"); }?>"
       min="2020-07-01" max="<?php echo date("Y-m-d"); ?>">
       <?php if(get_current_user_id()==64 OR get_current_user_id()==1 OR get_current_user_id()==30) { 

       	$users_array = get_userID_and_username();
       	//print_r($users_array);

       	?>

       	<input list="users" name="user" id="utilisateurs">
 		 <datalist id="users">
 		 	<?php

 		 	for ($i=1; $i <sizeof($users_array) ; $i++) { 
 		 		echo "<option value=\"".$users_array[$i]['display_name']."\">";
    	
    	}
    	?>

  	</datalist>

       	


       <?php } ?>
       <button type="submit">Aller à la date</button>

</form>













<?php
}
add_shortcode( 'statistiques' , 'stats_page' );
?>

