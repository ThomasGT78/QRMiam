<?php

/****************************************************************************************************
*										FUNCTION user_banner()										*
****************************************************************************************************/

add_shortcode( 'user_public_banner' , 'user_public_banner' );
// Shortcode used on page « profil »

function user_public_banner() {
	ob_start();
	if ($_GET['username']) {
		$cn_username = $_GET['username'];
		$cn_user_by_user = get_user_by( 'login', $cn_username); 
		$cn_user_by_user->user_login;
		$user_id = $cn_user_by_user->ID;
		$cn_user_documents = get_user_documents_By_Id($user_id);
		$cn_user_banner_details = get_user_banner_By_Id($user_id);

		$membership_product_date = get_user_meta($user_id,'membership_product_date',true);
		$membership_by = get_user_meta($user_id,'membership_by',true);
		$membership_product_id = get_user_meta($user_id,'membership_product_id',true);
		$membership_product_order_id = get_user_meta($user_id,'membership_product_order_id', true);	
		$order = wc_get_order($membership_product_order_id);


		// If Premium-Annual or Premium-Mensual or Premium-Essay
		if ($membership_product_id == 1107 || $membership_product_id == 236950 || $membership_product_id == 238609  || $membership_product_id == 3000) {
			$is_premium = 1;
			$is_lite = 0;
			$is_active = 1;
		} else if ($membership_product_id == 1105 || $membership_product_id == 236951) {
			$is_premium = 0;
			$is_lite = 1;
			$is_active = 1;
		} else {
			$is_active = 0;
		}

		////////////////////////////////////////////////////////////////////////////
		// if ($membership_product_id == 1105) {
		// 	//$completed='completed';
		// } else {
		// 	if ($membership_product_order_id) {
		// 		if ($order) {
		// 			$order->get_status();
		// 			if($order->get_status() == 'completed'){
		// 				$completed = 'completed';
		// 			} else {
		// 				$completed = 'incompleted';
		// 			}		
		// 		}
		// 	}
		// }

		// if ($membership_product_id == 1107 && $membership_by == 'admin') {
		// 	$completed='completed';
		// }
		////////////////////////////////////////////////////////////////////////////
	} // if ($_GET['username'])


	$cn_all_banner = get_option('cn_all_banner');
	$cn_all_banner_link = get_option('cn_all_banner_link');

	?>
	<div class="et_pb_row et_pb_row_0">
		<!-- <div class="et_pb_column et_pb_column_1 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough"> -->
		<?php 
		// if ($completed == 'completed') { 
		if ($is_premium) { 
			if (count($cn_user_banner_details == 1)) {
				$cn_user_banner_details[1] = $cn_user_banner_details[0];
				$cn_user_banner_details[0] = array();
			}
			if ($cn_user_banner_details[0]['file']) {
				$cn_link0 = $cn_user_banner_details[0]['link'];
				$cn_link_file0 = $cn_user_banner_details[0]['file'];
				$target0 = '_blank';
			} else {
				$cn_link = '#';
				$target = '_self';
				$cn_link0 = '';
				$cn_link_file0 = '';
			}
			if ($cn_user_banner_details[1]['file']) {
				$cn_link1 = $cn_user_banner_details[1]['link'];
				$cn_link_file1 = $cn_user_banner_details[1]['file'];
				$target1 = '_blank';
			} else {
				$cn_link = '#';
				$target1 ='_self';
				$cn_link1 = '';
				$cn_link_file1 = '';
			}
			if ($cn_user_banner_details[2]['file']) {
				$cn_link2 = $cn_user_banner_details[2]['link'];
				$cn_link_file2 = $cn_user_banner_details[2]['file'];
				$target2 = '_blank';
			} else {
				$cn_link = '#';
				$target2 = '_self';
				$cn_link2 = '';
				$cn_link_file2 = '';
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
				<a target="<?php echo $target2; ?>" href="<?php echo $cn_link2; ?>">
					<span class="et_pb_image_wrap ">
						<img src="<?php echo $cn_link_file2; ?>">
					</span>
				</a>
			</div>

		<?php 
		} // if ($completed == 'completed')
		else {
			if ($cn_all_banner) {
				// print_r($cn_all_banner);
				// if ($cn_all_banner_link[0]) {
				// 	$cn_link_banne0 = $cn_all_banner_link[0];
				// 	$img_banner0 = $cn_all_banner[0];
				// 	$target0 = '_blank';
				// } else {
				// 	$cn_link_banne0 = '#';
				// 	$img_banner0 = '';
				// 	$target0 = '_self';
				// }

				if ($cn_all_banner_link[1]) {
					$cn_link_banner1 = $cn_all_banner_link[1];
					$img_banner1 = $cn_all_banner[1];
					$target1 = '_blank';
				} else {
					$cn_link_banner1='#';
					$img_banner1='';
					$target1='_self';
				}

				// if ($cn_all_banner_link[2]) {
				// 	$img_banner2 = $cn_all_banner[2];
				// 	$cn_link_banner2 = $cn_all_banner_link[2];
				// 	$target2 = '_blank';
				// } else {
				// 	$cn_link_banner2 = '#';
				// 	$img_banner2 = '';
				// 	$target2 = '_self';
				// }
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

}  // function user_banner()

