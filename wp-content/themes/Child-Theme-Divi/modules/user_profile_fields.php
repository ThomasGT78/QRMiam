<?php

// cn_custom_user_profile_fields()
// wk_save_custom_user_profile_fields()

/****************************************************************************************************
*								FUNCTION cn_custom_user_profile_fields()							*
****************************************************************************************************/

add_action( 'edit_user_profile', 'cn_custom_user_profile_fields' );

function cn_custom_user_profile_fields( $user ) {

	$membership_product_id = get_user_meta($user->ID,'membership_product_id',true);
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


/****************************************************************************************************
*							FUNCTION wk_save_custom_user_profile_fields()							*
****************************************************************************************************/

add_action( 'edit_user_profile_update', 'wk_save_custom_user_profile_fields' );

function wk_save_custom_user_profile_fields( $user_id ) {
	$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
	$cn_time=date_format(date_create(date_i18n( $timezone_format )),"Y-m-d");
	$membership_plan = $_POST['membership_plan'];

	update_user_meta($user_id,'membership_product_date',$cn_time);
	update_user_meta($user_id,'membership_by','admin');
	update_user_meta($user_id,'membership_product_id',$membership_plan);
}

