<?php

// page		https://qrmiam.fr/profile/?username=t.garot
// page		https://qrmiam.fr/profile/?username=test

function test_expiration_date ($user_id, $expiration_limit){
	// Test the subscription date
	$membership_product_date = get_user_meta($user_id,'membership_product_date',true);
	$date = strtotime($membership_product_date);
	$expiration_date_not_formated = strtotime($expiration_limit, $date);
	$expiration_date = date('Y-m-d H:i:s', $expiration_date_not_formated);
	$today = date("Y-m-d H:i:s");

	if($today > $expiration_date){
		return $has_expired = true;
	} else {
		return $has_expired = false;
	}
}

function upload_mysql_and_monday($user_id, $new_membership_product_id){
	$user_monday_id = get_user_meta($user_id,'monday_item_id',true);
	update_user_meta($user_id,'membership_product_id',$new_membership_product_id);
	$list_statut = array('1105' => 2, '236951' => 158, '1107' => 4, '236950' => 8, '238609' => 3, '1000' => 0, '1500' => 1, '2000' => 10);
	$statut = $list_statut[$new_membership_product_id];
	monday_update_statut ($user_monday_id, $statut);
}
/**************************************************************************************************
 *									FUNCTION cn_public_page()									  *
 **************************************************************************************************/

add_shortcode('user_public_page', 'cn_public_page');
// Shortcode used on page « profil »

function cn_public_page(){ // Fin ligne 1484

	date_default_timezone_set('Europe/Paris');
	ob_start();

	$site_url = 'https://qrmiam.fr';
	global $wpdb;

	$k = 0;
	$stat_nbre_vue_table = $wpdb->prefix . 'stat_nbre_vue';


			/************************************************
			*	 If there is a valid username in the URL	*
			************************************************/

	if ($_GET['username']) {
		$user_login = $_GET['username'];
		$user_id = get_userID_By_username($user_login);
		// echo $user_id."<br>";

		// TEST CODE ONLY ON ADMIN PROFILE
		if ($user_id == 40){
			// pop_up($user_id);
			// delete_docs_unused();
			// delete_docs_users_unused();
		}


		if($user_id != 40){ // Doesn't work for admin profile in case of change of status while testing
			delete_banner_and_pdf_if_is_lite($user_id);
		}


		// TEST
		// if ($user_id == 40 OR $user_id == 645){
		// 	// echo "OK";
		// 	upload_user_statut($user_id);
		// }
		upload_profil_user_public($user_id);

			/****************************************************
			*	Manage Subscription type and expiration date	*
			****************************************************/
		
		$membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		$list_status_simple = [
			'1105'=> "actif yearly",	// lite yearly
			'236951'=> "actif monthly",	// lite monthly
			'1107'=> "actif yearly",	// premium yearly
			'236950'=> "actif monthly",	// premium monthly
			'238609'=> "essai",
			'1000'=> "non-actif",
			'2000'=> "perdu",
			'3000'=> "gratuit",
		];
		
		$simple_status = $list_status_simple[$membership_product_id];
		
		if($simple_status == "actif yearly"){
			$expiration_limit = '+ 1 year';
			$has_expired = test_expiration_date ($user_id, $expiration_limit);
			
			if($has_expired){
				$new_membership_product_id = 2000;
				upload_mysql_and_monday($user_id, $new_membership_product_id);
			}
		} 
		elseif($simple_status == "actif monthly"){
			$expiration_limit = '+ 1 month';
			$has_expired = test_expiration_date ($user_id, $expiration_limit);
			
			if($has_expired){
				$new_membership_product_id = 2000;
				upload_mysql_and_monday($user_id, $new_membership_product_id);
			}
		} 
		elseif ($simple_status == "essai") {
			$expiration_limit = '+ 7 day';
			$has_expired = test_expiration_date ($user_id, $expiration_limit);
			
			if($has_expired){
				$new_membership_product_id = 1000;
				upload_mysql_and_monday($user_id, $new_membership_product_id);
			}
		} 
		elseif ($simple_status == "gratuit") {
			$expiration_limit = '+ 10 day';
			$has_expired = test_expiration_date ($user_id, $expiration_limit);
			
			if($has_expired){
				$new_membership_product_id = 1000;
				upload_mysql_and_monday($user_id, $new_membership_product_id);
			}
		} 
		

				/************************************
				*	Add 1 view to the statistics	*
				************************************/

		$stat_nbre_vue = get_stat_nbre_vue_By_user_id_now($user_id);
		$date = date("Y-m-d");
		// $date = "'" . date("Y-m-d") . "'";
		$heure = date("H");

		//Si il existe déja un champ pour l'user_id à la date et heure de maintenant
		if($stat_nbre_vue){

					$result_data = array('nbre_vue' => $stat_nbre_vue[0]['nbre_vue']+1);

					$response = iUpdateArrayMultiConds(
						$stat_nbre_vue_table,
						$result_data,
						array(
							'user_id' => $user_id,
							'date' => $date,
							'heure' => $heure
						)
					);
					$response = json_decode($response);
		}
		else {
			insert_stat_nbre_vue();
		}


		// } // if ($_GET['username'])

		// $upload = wp_upload_dir();
		// $upload_dir = $upload['basedir'];
		// $upload_url = $upload['baseurl'];
		$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
		$cn_date = date('m/d/Y h:i:sa', time());
		$cn_time = date_format(date_create(date_i18n( $timezone_format )),"H:i");
	
	
		if ($simple_status == "essai" || $simple_status == "gratuit" || $simple_status == "actif yearly" || $simple_status == "actif monthly") {
			// $all_user_meta = get_user_meta($user_id);
			$all_user_meta = get_usermeta_by_ID($user_id);

			$cn_user_documents = get_user_documents_By_Id($user_id);
			
			$etablissement_color = get_etablissement_color_By_username($user_login);
			$user_etablissement_color = $etablissement_color[0]['etablissement_color'];
			
			// $user_etablissement_name = $all_user_meta['user_registration_nom_etablissement'][0];
			$user_etablissement_name = $all_user_meta['user_registration_nom_etablissement'];

			if($all_user_meta['name_style']){
				// $name_style = $all_user_meta['name_style'][0];
				$name_style = $all_user_meta['name_style'];
			} else {
				$name_style = "Roboto";
			}
			if($all_user_meta['name_size']){
				// $name_size = $all_user_meta['name_size'][0]."px";
				$name_size = $all_user_meta['name_size']."px";
			} else {
				$name_size = "1rem";
			}
			
		?>
		


	<!--*************************************************************************************************
	*											STYLE CSS												*
	**************************************************************************************************-->


			<style type="text/css" media="screen">
				@font-face {
					font-family: 'Arvo';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/arvo/v14/tDbD2oWUg0MKqScQ7Q.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Bebas Neue';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/bebasneue/v2/JTUSjIg69CK48gW7PXoo9Wlhyw.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Bodoni Moda';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/bodonimoda/v7/aFT67PxzY382XsXX63LUYL6GYFcan6NJrKp-VPjfJMShrpsGFUt8oU7a8Il4tGjM.woff2) format('woff2');
					unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
				}
				@font-face {
					font-family: 'Bree Serif';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/breeserif/v10/4UaHrEJCrhhnVA3DgluA96rp5w.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Crimson Text';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/crimsontext/v11/wlp2gwHKFkZgtmSR3NB0oRJfbwhT.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Indie Flower';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/indieflower/v12/m8JVjfNVeKWVnh3QMuKkFcZVaUuH.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Libre Baskerville';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/librebaskerville/v9/kmKnZrc3Hgbbcjq75U4uslyuy4kn0qNZaxM.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Oswald';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/oswald/v36/TK3_WkUHHAIjg75cFRf3bXL8LICs1_FvsUZiZQ.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}
				@font-face {
					font-family: 'Roboto';
					font-style: normal;
					font-weight: 400;
					font-display: swap;
					src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
					unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
				}

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
				.text-center {
					text-align: center;
				}
				.etablishment-name{
					color:<?php echo $user_etablissement_color; ?>;
					font-family: <?php echo $name_style ?>;
					font-size: <?php echo $name_size ?>;
				}
				/* et_pb_gallery_image */
				.div-bloc-document p { 
					margin-top: 5px; 
					text-align: center; 
					font-size: 17px;
					min-width: min-content;
					height: min-content;
				}
			</style>


	<!--*************************************************************************************************
	*											SCRIPT JS												*
	**************************************************************************************************-->

	<!-- 	<script type="text/javascript">
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


<!--*************************************************************************************************
*											PAGE HTML												*
**************************************************************************************************-->
			
			<div class="Uploaded_doc">

				<h2 class="text-center etablishment-name" >
					<?php echo $user_etablissement_name; ?>
					
				</h2>
				
				<br>

				<?php
				if ($cn_time=='00:00') {
					$cn_time='24:00';
				}

				if ($cn_user_documents) {
						
					foreach ($cn_user_documents as $value) {
						?> <?php
						$fileext = explode('.', $value['file']);

						if ($value['cn_from']!='') {

							if ($value['cn_to']=='00:00') {
								$value['cn_to']='24:00';
							}

							if ($cn_time>$value['cn_from'] && $cn_time<$value['cn_to']) {
							?>
								<div cn_time="<?php echo $cn_time ?>" cn_to="<?php echo $value['cn_to']?>" cn_from="<?php echo $value['cn_from'] ?>" class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">

									<div class="div-bloc-document landscape">

										<?php 
										if ($fileext[2]=='pdf') {
										?>
											<a href="<?php echo $value['file']; ?>?#zoom=0">
												<img src="<?= $site_url."/wp-content/uploads/2020/06/pdf_tb.png" ?>" alt="" style="width: 100%;">	
												<p style="text-align: center;">
													<?php echo htmlspecialchars($value['title']); ?>
												</p>
											</a>	
										<?php
										} else {
										?>
											<a data-fancybox="gallery" data-options='{"buttons": ["zoom","thumbs","close"]}' href="<?php echo $value['file']; ?>">
												<img src="<?php echo $value['file']; ?>">
												<p style="color:<?php echo $value['color']; ?>;">
													<?php echo htmlspecialchars($value['title']); ?>
												</p>
											</a>

											<!--
											<a href="<?php echo $value['file']; ?>" title="">
												<img src="<?php echo $value['file']; ?>" srcset="<?php echo $value['file']; ?> 479w, <?php echo $value['file']; ?> 480w" sizes="(max-width:479px) 479px, 100vw">
												<span class="et_overlay"></span>
											</a> 
											-->	
										<?php
											}
										?>
										
									</div>
								</div>
							<?php
							} // if ($cn_time>$value['cn_from'] && $cn_time<$value['cn_to'])

						} // if ($value['cn_from']!='')
						else {
						?>
						<?php 
						if ($fileext[2]=='pdf') {
							?>
							<div class="et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">
								<div class="landscape">
									<a target="_blank" href="<?php echo $value['file']; ?>?#zoom=0">
										<img src="<?= $site_url."/wp-content/uploads/2020/06/pdf_tb.png" ?>" alt="" style="width: 100%;">	
										
										<p style="    text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo htmlspecialchars($value['title']); ?></p>
									</a>	
								</div>
							</div>

						<?php
						} // if ($fileext[2]=='pdf') 
						else {
						?>
							<div class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light et_pb_gallery_item_0_0 first_in_row" style="display: block;">

								<div class="div-bloc-document landscape">

									<a data-fancybox="gallery" data-options='{"buttons": ["thumbs","close"]}' href="<?php echo $value['file']; ?>">
										<img src="<?php echo $value['file']; ?>">
										<p style="color:<?php echo $value['color']; ?>;">
											<?php echo htmlspecialchars($value['title']); ?>
										</p>
									</a>
									<!-- 	
									<a href="<?php echo $value['file']; ?>" title="">
										<img src="<?php echo $value['file']; ?>" srcset="<?php echo $value['file']; ?> 479w, <?php echo $value['file']; ?> 480w" sizes="(max-width:479px) 479px, 100vw">
										<span class="et_overlay"></span>
										<p style="   margin-top: 17px; text-align: center; color:<?php echo $value['color']; ?>; font-size: 17px;"><?php echo $value['title']; ?></p>
									</a>	 
									-->
								</div>
							</div>
						<?php 
						} // else
						?>
								
						<?php
						} // else ..if
						
					} // end for
				} // if ($cn_user_documents)
				else {
				?>
					<h2 style="text-align: center;">Aucun document disponible</h2>
				<?php
				}?>
				
			</div>

		<?php
		} // if (actif or gratuit or essai)
		else {
			echo '<h3 style="text-align: center;">Utilisateur non actif</h3>';
		}
	} // if ($_GET['username'])
	else {
		echo '<h3 style="text-align: center;">Utilisateur non trouvé</h3>';
	}
	
	$ReturnString = ob_get_contents();
	ob_end_clean();

	return $ReturnString;

} // function cn_public_page() - Ligne 1147

